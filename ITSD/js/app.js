angular.module('itsteady', [])
.controller('MainController', ['$scope', '$http', function($scope, $http) {
  $scope.menus=[
    { text: "COMPANY", link: "#company", active: true},
    { text: "OUR SERVICES", link: "#ourservices"},
    { text: "TECHNOLOGIES", link: "#technology"},
    { text: "HOW WE WORK", link: "#howwework"},
    { text: "CLIENTS", link: "#clients"},
    { text: "CAREERS", link: "#careers"},
    { text: "TEAM", link: "#team"},
    { text: "STAY IN TOUCH", link: "#contactus"},
  ];

  $scope.technologies = [{
    img: "images/tech_logo1.png",
    title: ".NET, Microsoft Technologies",
    text:"Enterprise systems development, collaboration solutions, E-learning solutions"
  },{
    img: "images/tech_logo2.png",
    title: "MS Dynamics CRM",
    text:"Customer relations solution, Loyalty, customer portals, CRM adoption and implementations"
  },{
    img: "images/tech_logo3.png",
    title: "Sharepoint",
    text:"Ut enim ad minima veniam, quis nostrum exercitationem ullam"
  },{
    img: "images/tech_logo4.png",
    title: "PHP/LAMP",
    text:"Customer relations solution, Loyalty, customer portals, CRM adoption and implementations"
  },{
    img: "images/tech_logo5.png",
    title: "JAVA",
    text:"Enterprise systems development, collaboration solutions, E-learning solutions"
  },{
    img: "images/tech_logo6.png",
    title: "iOS/Android/Windows",
    text:"Customer relations solution, Loyalty, customer portals, CRM adoption and implementations"
  }];
 // $scope.myForm = {
 //  send:function(item, event) {
 //   console.log("--> Sending form");
 //   var dataObject = {
 //      name : $scope.myForm.name
 //      ,email  : $scope.myForm.email
 //      ,phone  : $scope.myForm.phone
 //      ,message  : $scope.myForm.message
 //      ,attach  : $scope.myForm.attach
 //   };

 //   var responsePromise = $http.post("upload.php", dataObject, {});
 //   responsePromise.success(function(dataFromServer, status, headers, config) {
 //      console.log(dataFromServer.title);
 //   });
 //    responsePromise.error(function(data, status, headers, config) {
 //      alert("Sending file failed!");
 //   });
 //  }
 // };

}]);
