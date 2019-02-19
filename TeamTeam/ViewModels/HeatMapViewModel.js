//the view model
var HeatMapViewModel = function() {
    self = this;

    self.width = ko.observable(450);
    self.height = ko.observable(300);
    self.radius = ko.observable(200);

    self.value1 = ko.observable(Math.floor(Math.random()*20 + 60));
    self.value2 = ko.observable(Math.floor(Math.random() * 20 + 60));
    self.value3 = ko.observable(Math.floor(Math.random() * 20 + 60));
    self.value4 = ko.observable(Math.floor(Math.random() * 20 + 60));
    self.value5 = ko.observable(Math.floor(Math.random() * 20 + 60));
    self.value6 = ko.observable(Math.floor(Math.random() * 20 + 60));

    //function used when submit button is clicked
    self.submit = function () {
        console.log("no");
        self.value1(Math.floor(Math.random() * 20 + 60));
        self.value2(Math.floor(Math.random() * 20 + 60));
        self.value3(Math.floor(Math.random() * 20 + 60));
        self.value4(Math.floor(Math.random() * 20 + 60));
        self.value5(Math.floor(Math.random() * 20 + 60));
        self.value6(Math.floor(Math.random() * 20 + 60));
        initialize();
    }

    self.value1.subscribe(function (newValue) {
        initialize();
    });


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


    self.heatmap = h337.create({
        container: document.getElementById('heatmapContainer'),
        gradient:
        {
            0.15: "rgb(0,0,255)",
            0.50: "rgb(0,255,0)",
            0.70: "rgb(255, 165, 0)",
            1.00: "rgb(255,0,0)"
        }
    });

    initialize = function () {
        // boundaries for data generation

        self.heatmap.setData({
            min: 60,
            max: 80,
            data: [
                {//Node 1
                    x: self.width() / 8,
                    y: self.height() / 4,
                    value: self.value1(),
                    radius: self.radius()

                },
                {//Node 2
                    x: self.width() / 2,
                    y: self.height() / 4,
                    value: self.value2(),
                    radius: self.radius()
                },
                {//Node 3
                    x: self.width() / 8 * 7,
                    y: self.height() / 4,
                    value: self.value3(),
                    radius: self.radius()
                },
                {//Node 4
                    x: self.width() / 8,
                    y: self.height() / 4 * 3,
                    value: self.value4(),
                    radius: self.radius()
                },
                {//Node 5
                    x: self.width() / 2,
                    y: self.height() / 4 * 3,
                    value: self.value5(),
                    radius: self.radius()
                },
                {//Node 6
                    x: self.width() / 8 * 7,
                    y: self.height() / 4 * 3,
                    value: self.value6(),
                    radius: self.radius()
                }
            ]
        });


        self.heatmap.setDataMax(77);
        self.heatmap.setDataMin(61);


        
    }

    initialize();
};

//binding the viewmodel to the view
ko.applyBindings(new HeatMapViewModel());
