//ViewModel for Recommendations
var RecommendationsViewModel = function() {
    self = this;
    //inputs
    self.buttonLabel = ko.observable("Man's Not Hot");

    self.neverHot = ko.observable(false);

    //function used when submit button is clicked
    self.submit = function () {
        if (self.buttonLabel() == "Man's Not Hot")
            self.buttonLabel("Never Hot");
        else
            self.buttonLabel("Man's Not Hot");
        self.neverHot(!self.neverHot());
        window.open("https://www.youtube.com/watch?v=2PjNsPMKqSA", "_blank");
    };

    self.submitAlso = function () {

    };

};

ko.applyBindings(new RecommendationsViewModel());
