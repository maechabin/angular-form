var angular = angular || {};
var formApp = angular.module("formApp", []);

formApp.config(function ($locationProvider) {
  "use strict";
  $locationProvider.html5Mode(true);
});

formApp.controller("FormController", ["$scope", "$http", "$location", function ($scope, $http, $location) {

  "use strict";

  $http.get("/pgreq/sprice/pg.json")
    .success(function (data) {
      $scope.pid = $location.search().pid;
      $scope.pgData = data;
      var pgcheckFlag = 0;

      angular.forEach($scope.pgData, function (pg, i) {

        if (pg.id === $scope.pid) {
          pgcheckFlag = 1;
          return false;
        }

      });

      if ($scope.pid === undefined || pgcheckFlag === 0) {
        $location.hash("not_found");
        angular.element(".main").html('<p class="form__list_message"><b>404 Not Found.</b><br>お探しのページは見つかりませんでした。</p>');
        ga('send', 'pageview', location.pathname + "?" + location.hash.split('#')[1]);
      }

    })
    .error(function (data) {
      $scope.data.error = status;
    });

  $scope.user = {
    asid: $location.search().asid
  };

  $scope.master = {};

  $scope.submit = function (user) {

    var that = this;
    that.master = angular.copy(user);
    that.master.pid = $scope.pid;

    angular.forEach($scope.pgData, function (pg, i) {

      if (pg.id === $scope.pid) {
        that.master.pname = pg.name;
      }

    });

    //console.log(that.master);

    $http({
      method: "GET",
      url: "/pgreq/sprice/php/index.php",
      params: that.master
    })
    .success(function (data, status, headers, config) {
      //console.dir(arguments);
      that.message = "success";
      $location.hash(that.message);
      angular.element(".form__list_wrapper").html('<p class="form__list_message">リクエストを受け付けました。<br>ありがとうございます。</p>');
      ga('send', 'pageview', location.pathname + "?" + location.hash.split('#')[1]);
    })
    .error(function (data, status, headers, config) {
      that.message = "failed";
      $location.hash(that.message);
      angular.element(".form__list_wrapper").html('<p class="form__list_message">リクエストの送信に失敗しました。<br>時間をおいてから再度お試しください。</p>');
      ga('send', 'pageview', location.pathname + "?" + location.hash.split('#')[1]);
    });

  };

}]);
