// Importing the TicketModel
var imported = document.createElement('script');
imported.src = '../Models/TicketModel.js';
document.head.appendChild(imported);

//ViewModel for LookupMyTicket
var LookupMyTicketViewModel = function() {
    self = this;
    //inputs
    self.inputName = ko.observable("");
    self.inputFlightNumber = ko.observable("");

    //outputs
    self.flightNumber = ko.observable("");
    self.ticketNumber = ko.observable("");
    self.name = ko.observable("");
    self.price = ko.observable("");
    self.departureLocation = ko.observable("");
    self.destination = ko.observable("");
    self.departureDate = ko.observable("");
    self.arrivalDate = ko.observable("");

    //Object representing the data we will fetch from databse
    self.ticket = ko.observable();

    //function used when submit button is clicked
    self.submit = function() {
      //this is sample data that should be popualted from the database based on the inputs
      self.ticket = new Ticket("flight Number", "Ticket Number", "Name", "Price", "Location", "Destination", "Departure Date", "Arrival Date");

      //Presenting input data into the table
      self.name(self.inputName());
      self.flightNumber(self.inputFlightNumber());

      //submitting the data into the table
      self.ticketNumber(self.ticket.TicketNumber());
      self.price(self.ticket.Price());
      self.departureLocation(self.ticket.Location());
      self.destination(self.ticket.Destination());
      self.departureDate(self.ticket.DepartureDate());
      self.arrivalDate(self.ticket.ArrivalDate());
    }

};

ko.applyBindings(new LookupMyTicketViewModel());
