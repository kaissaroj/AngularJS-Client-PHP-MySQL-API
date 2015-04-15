/**
 * Created by Saroj on 1/3/15
 */
var app = angular.module('myApp', ['ngSanitize']);
app.controller('taskController', function ($scope, $http, $sce) {
  $scope.products = [];
  $scope.minValue = [[]];
  $scope.maxValue = [[]];
  $scope.retailValue = [[]];
  $scope.profitValue = [[]];
  $scope.priceIdValue = [[]];
  $scope.isEditing = [];

  function getList() {
    $http({
      method: 'POST',
      url: "ajax/getList.php"
    }).success(function (response) {
      if (response.length === undefined) {
        return;
      }
      response.forEach(function (product) {

        $scope.minValue[product.id] = [];
        $scope.maxValue[product.id] = [];
        $scope.retailValue[product.id] = [];
        $scope.profitValue[product.id] = [];
        $scope.priceIdValue[product.id] = [];
        var pricesWithCount = [];
        var isLastValueUnlim = false;
        var maxVal = 0;

        for (var pricesCount = 0; pricesCount < product.prices.length; pricesCount++) {
          $scope.minValue[product.id].push(product.prices[pricesCount].min);

          // This cannot be 0, only Unlim, which is stored as 0 in the DB
          // Instead of showing 0, lets clear the field
          maxVal = product.prices[pricesCount].max;
          if (maxVal == 0) { // yes, == only, as there can be '0' char or null.
            isLastValueUnlim = true;
            maxVal = ''; // *le blank
          } else {
            isLastValueUnlim = false; // just in case
          }

          $scope.maxValue[product.id].push(maxVal);

          $scope.retailValue[product.id].push(product.prices[pricesCount].rate);
          $scope.profitValue[product.id].push(product.prices[pricesCount].rate * (100 - product.perc) / 100);
          $scope.priceIdValue[product.id].push(product.prices[pricesCount].id);

          pricesWithCount.push({
            max: product.prices.max,
            min: product.prices.min,
            rate: product.prices.rate,
            count: pricesCount,
            priceId: pricesCount.id
          });
        }

        // test
        console.log(product.prices.min);
        console.log(parseInt(product.prices.max) + 1);

        // add new pricesWithCount (the last row) if last value is not unlim
        if (!isLastValueUnlim) {
          pricesWithCount.push({
            max: "",
            min: maxVal,
            rate: "",
            count: pricesWithCount.length,
            priceId: null
          });
        }

        $scope.products[product.id] = {
          name: product.name,
          id: product.id,
          perc: product.perc,
          prices: pricesWithCount
        };
      });
    }).error(function (err) {
      alert(err);
    });
  }
  getList();
  /*
   *adding new Trip or Product
   */
  $scope.newTrip = function () {
    var name = $scope.newProductName;
    var perc = $scope.newProductPerc;
    if (!name || !perc) {
      alert("All details are required.");
      return;
    }

    $scope.products["new"] = {id: null, name: name, perc: perc, prices: []};
    var urlToCall = "ajax/addTrip.php?trip=" + name + "&perc=" + perc;
    console.log(urlToCall);
    $http({
      method: 'POST',
      url: urlToCall
    }).success(function (response /* id of newly created trip */) {
      console.log(response);

      // add new pricesWithCount (the last row)
      var pricesWithCount = [];
      $scope.minValue[response] = [];
      $scope.maxValue[response] = [];
      $scope.retailValue[response] = [];
      $scope.profitValue[response] = [];
      $scope.priceIdValue[response] = [];
      pricesWithCount.push({
        max: "",
        min: "",
        rate: perc,
        count: 0,
        priceId: null
      });
      $scope.products[response] = {
        name: name,
        id: response,
        perc: perc,
        prices: pricesWithCount
      };

      // Try to focus the first input field
      window.setTimeout(function () {
        document.getElementById('inp' + response + '_minNo' + 0).focus();
      }, 100);

      // Save some memory where we can
      $scope.products["new"] = null;
      $scope.newProductName = null;
      $scope.newProductPerc = null;
    }).error(function (err) {
      alert(err);
    });
  };

  $scope.startEditing = function (productId) {
    $scope.isEditing[productId] = true;
    $scope.updatedName = $scope.products[productId].name;
    $scope.updatedPerc = $scope.products[productId].perc;
  };

  $scope.updateTrip = function (productId) {

    var name = document.getElementById("updatedName").value;
    var perc = parseInt(document.getElementById("updatedPerc").value);

    var urlToCall = "ajax/addTrip.php?trip=" + name + "&perc=" + perc + "&id=" + productId;
    console.log(urlToCall);
    $http({
      method: 'POST',
      url: urlToCall
    }).success(function (response /* id of the updated trip */) {
      console.log(response);
      $scope.products[productId].name = name;
      $scope.products[productId].perc = perc;

      document.getElementById("updatedName").value = "";
      document.getElementById("updatedPerc").value = "";

      $scope.isEditing[productId] = false;

      // Repopulate the profit fields with results of new calculation
      // Timeout of a short period to make noticeable
      for (var i = 0; i < $scope.retailValue[productId].length; i++) {
        $scope.profitValue[productId][i] = (100 - perc) * $scope.retailValue[productId][i] / 100;
      }
    }).error(function (response) {
      console.error(response);
    });
  };

  /*
   *Adding Price List to database
   */
  $scope.newPrice = function (productId, rowCount) {

    var minNo = $scope.minValue[productId][rowCount] || 0;
    var maxNo = $scope.maxValue[productId][rowCount] || 0;
    var retail = $scope.retailValue[productId][rowCount] || 0;
    var priceId = $scope.priceIdValue[productId][rowCount] || 0;

    $scope.profitValue[productId][rowCount] = retail * (100 - $scope.products[productId].perc) / 100;
    if (!minNo || !retail) {
      console.log("No data to proceed. Ignoring enter key.");
      return;
    }

    var urlToCall = "ajax/addPrice.php?productId=" + productId + "&minNo=" + minNo
            + "&maxNo=" + maxNo + "&retail=" + retail;

    if (priceId) {
      urlToCall += "&priceId=" + priceId;
    }

    console.log(urlToCall);
    $http({
      url: urlToCall
    }).success(function (response /* id of newly added price */) {

      $scope.priceIdValue[productId][rowCount] = response;

      if (maxNo) {
        addNewPriceRow(productId, rowCount);
      }
    }).error(function (response) {
      alert(response);
    });

  };
  function addNewPriceRow(productId, rowCount) {
    /*
     Before adding a new line, see if it exists.
     If it does, simply focus on the first field
     of that line.
     */
    var nextLine = rowCount + 1;
    var nextLineId = 'inp' + productId + '_minNo' + nextLine;
    var tableId = 'inp' + productId + '_profit' + nextLine;
    console.log("Checking next line id: " + nextLineId);
    if (null !== document.getElementById(nextLineId)) {
      console.log("Already exists. Focusing.");
      document.getElementById(nextLineId).focus();
      return;
    } else {
      console.log("Adding new.");
      var newRowofPrices = {
        "min": "",
        "max": "",
        "rate": "",
        "perc": "",
        "priceId": null,
        "count": ++rowCount
      };
      console.log("Adding new row: " + newRowofPrices.count);
      $scope.products[productId].prices.push(newRowofPrices);
      window.setTimeout(function () {
        $scope.minValue[productId][rowCount] = parseInt($scope.maxValue[productId][rowCount - 1]) + 1;
        document.getElementById('inp' + productId + '_maxNo' + rowCount).focus();
      }, 100);
    }
  }
  /*
   *Deleting the product
   */
  $scope.deleteTrip = function (id) {
    var tmpProduct = $scope.products[id];
    console.dir(tmpProduct);
    $scope.products[id] = null;
    $http({
      method: 'POST',
      url: "ajax/deleteTrip.php?pid=" + id
    }).success(function (response) {
      // Let it be!
    }).error(function (error) {
      $scope.products[id] = tmpProduct;
    });
  };
  /*
   *Deleting the last price range
   */
  $scope.deleteLastPrice = function (productId, rowCount, lastPriceId, secondLastPriceId) {

    if (!lastPriceId) {
      console.log("Removing dummy row");
      $scope.products[productId].prices.pop();
      return;
    }

    var urlToCall = "ajax/deletePrice.php?lastId=" + lastPriceId
            + "&secondLastId=" + secondLastPriceId + "&lastValue=";

    urlToCall += ($scope.maxValue[productId][rowCount]) ? $scope.maxValue[productId][rowCount] : 0;

    console.log(urlToCall);
    $http({
      method: 'POST',
      url: urlToCall
    }).success(function (response) {

      $scope.maxValue[productId][rowCount - 1] = $scope.maxValue[productId][rowCount];

      $scope.minValue[productId][rowCount] = '';
      $scope.maxValue[productId][rowCount] = '';
      $scope.retailValue[productId][rowCount] = '';
      $scope.priceIdValue[productId][rowCount] = '';

      $scope.products[productId].prices.pop();
    }).error(function (error) {
      $scope.products[id] = tmpProduct;
    });
  };
});

// That enter key on the Retail number!
app.directive('ngEnter', function () {
  return function (scope, element, attrs) {
    element.bind("keydown keypress", function (event) {
      if (event.which === 13) {
        scope.$apply(function () {
          scope.$eval(attrs.ngEnter);
        });
        event.preventDefault();
      }
    });
  };
});


// That enter key on the Retail number!
app.directive('ngNumeric', function () {
  return function (scope, element, attrs) {
    element.bind("keydown", function (event) {
      scope.$apply(function () {
//        var intVal = $(element).val().replace(/\D/, '');
//        console.log(intVal);
//        if (!isNaN(intVal)) {
//          $(element).val(intVal);
//        }

        console.log(event.which);

        if (event.which >= 65 && event.which <= 90) {
          //alert("NO");
          event.preventDefault();
        }
      });
    });
  };
});
