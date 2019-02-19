// importing the FlightModel
var imported = document.createElement('script');
imported.src = '../Models/FlightModel.js';
document.head.appendChild(imported);


//the view model
var SearchForFlightsViewModel = function() {
    self = this;

    //inputs
    self.DepartureLocation = ko.observable();
    self.Destination = ko.observable();
    self.DepartureDate = ko.observable();
    self.ArrivalDate = ko.observable();
    self.PriceFrom = ko.observable();
    self.PriceTo = ko.observable();

    //This array which will contain the list of flights from the database
    self.flights = ko.observableArray();

    //function used when submit button is clicked
    self.submit = function() {
      //just pushing garbage data into the array
      self.flights.push(new Flight("flight1", "100", "location1", "destination1", "today", "tomorrow"));
      self.flights.push(new Flight("flight2", 100, "location2", "destination2", new Date("December 17, 1995 03:24:00"), new Date("December 17, 1995 03:24:00")));
      self.flights.push(new Flight("flight3", 100, "location2", "destination3", new Date("December 17, 1995 03:24:00"), new Date("December 17, 1995 03:24:00")));
    }
};

//binding the viewmodel to the view
ko.applyBindings(new SearchForFlightsViewModel());
