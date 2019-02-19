//the view model
var HeatMapViewModel = function() {
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

                },
            error: function (data) {
                self.submit();
            }
        });
    }
};

//binding the viewmodel to the view
ko.applyBindings(new HeatMapViewModel());
