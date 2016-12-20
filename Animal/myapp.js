//Matthew Hoover - Demo 
var littleApp = angular.module('littleApp', []);
//Request JSON data
littleApp.factory('itemsFactory', ['$http', function($http){
  var itemsFactory ={
    itemDetails: function() {
      return $http(
      {
        url: "inventory.json",
        method: "GET",
      })
      .then(function (response) {
        return response.data.inventory;
        });
      }
    };
    return itemsFactory;
}]);

//Controller
littleApp.controller('ListCtrl',
['$scope', 'itemsFactory', function($scope, itemsFactory){
  var promise = itemsFactory.itemDetails();

    promise.then(function (data) {
        $scope.itemDetails = data; //Add JSON data

    });
    $scope.select = function(item) {
      $scope.selected = item; //Add selected item
    }
    $scope.selected = {}; //selected item
}]);
