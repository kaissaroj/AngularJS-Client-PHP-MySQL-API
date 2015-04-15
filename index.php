<?php
/**
 * Created by Saroj on 1/3/15
 */

if (!file_exists('dbconfig')) {
  header("Location: install.php");
  exit;
}
?><!DOCTYPE html>
<html>
  <head lang="en">
    <meta charset="UTF-8">
    <title>Trip Rate</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <script type="text/javascript" src="js/angular.min.js"></script>
    <script type="text/javascript" src="js/angular-sanitize.min.js"></script>
  </head>
  <body ng-app="myApp">
    <div class="container container-fluid" ng-controller="taskController">
      <div class="row">
        <div class="col-mg-12">
          <h1>Product Booking <span class="text-muted">CRUD Demo</span></h1>
          <div class="alert pull-left" style="width: 100%;">
            <h4>Add a New Trip</h4>
            <form style="margin: 0; padding: 0;">
              <input class="form-control" placeholder="Name of the trip" type="text" ng-model="newProductName" style="float: left; height: 2.5em; width: 60%;">
              <input class="form-control" placeholder="% Commission" type="number"  ng-model="newProductPerc" ng-numeric="true" style="float: left; height: 2.5em; width: 25%">
              <button class="btn btn-success" ng-click="newTrip()"
                      style="float: left; height: 2.5em; width:15%">
                <i class ="icon-plus"> </i> Add
              </button>
            </form>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <!-- one collapsible section -->
          <div ng-repeat="product in products track by $index" ng-if="product.id !== undefined">
            <div class="panel-group" id="accordion">
              <div class="panel panel-default" style="padding: 0">
                <div class="panel-heading">
                  <a  ng-if="isEditing[product.id] !== true" data-toggle="collapse" data-target="#collapse_{{product.id}}" href="#collapseOne">{{product.name}}</a>
                  <span ng-if="isEditing[product.id] === true">
                    <input type="text" value="{{product.name}}" placeholder="New name" id="updatedName">
                    <input type="text" value="{{product.perc}}" placeholder="New %" id="updatedPerc">
                    <i class="btn btn-success btn-sm icon-save"
                       ng-click="updateTrip(product.id)" > </i>
                  </span>

                  <i class="btn btn-danger btn-xs icon-remove text-danger pull-right"
                     ng-click="deleteTrip(product.id)"></i>
                  <i class="btn btn-info btn-xs icon-pencil text-info pull-left"
                     ng-click="startEditing(product.id)"></i>
                </div>
                <div id="collapse_{{product.id}}" class="panel-collapse collapse in">
                  <div class="panel-body" style="padding: 0">
                    <table class="table table-striped" style="margin: 0;">
                      <thead>
                        <tr>
                          <th style="width: 20%;">People (Min)</th>
                          <th style="width: 20%;">People (Max)</th>
                          <th style="width: 35%;">Rate Per Person</th>
                          <th style="width: 20%;">Profit -{{product.perc}}% commission</th>
                          <th style="width: 5%; text-align: right">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr ng-repeat="row in product.prices track by $index"
                            id="table[product.id][row.count]">
                          <td>
                            <input type="text" class="form-control" ng-model="minValue[product.id][row.count]" id="inp{{product.id}}_minNo{{row.count}}" placeholder="0" ng-numeric="true" >
                          </td>
                          <td>
                            <input type="text" class="form-control" ng-model="maxValue[product.id][row.count]" placeholder="Unlimited" id="inp{{product.id}}_maxNo{{row.count}}" ng-numeric="true">
                          </td>
                          <td>
                            <div class="input-group pull-left">

                              <input type="text" class="form-control pull-right" ng-model="retailValue[product.id][row.count]" ng-enter="newPrice({{product.id}}, {{row.count}})"
                                     id="inp{{product.id}}_retailValue{{row.count}}"
                                     ng-numeric="true" >
                              <span class="input-group-addon"><small>
                                  <i class="icon-info-sign"></i> Hit [Enter]</small></span>
                            </div>
                          </td>
                          <td>
                            <input class="form-control pull-left"
                                   ng-model="profitValue[product.id][row.count]" ng-disabled="true">


                            <input type="hidden" ng-model="priceIdValue[product.id][row.count]">
                          </td>
                          <td>
                            <i ng-if="row.count !== 0 && (row.count == product.prices.length - 1)" class="btn icon-remove text-danger pull-right" ng-click="deleteLastPrice(product.id, row.count, priceIdValue[product.id][row.count], priceIdValue[product.id][row.count - 1])"></i>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- end one collapsible section -->
    </div>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="index.js"></script>
  </body>
</html>
