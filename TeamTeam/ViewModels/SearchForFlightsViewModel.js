//the view model
var SearchForFlightsViewModel = function() {
    self = this;


    //function used when submit button is clicked
    self.submit = function() {

    }

    self.submitPostSurvey = function () {

        var url = window.location.origin +
            "/api/Vue/CreateVueSurvey";

        $.ajax({
            url: url,
            dataType: "json",
            success: function (data) {

                }
            },
            error: function (data) {

            }
        });
    }
};

//binding the viewmodel to the view
ko.applyBindings(new SearchForFlightsViewModel());
