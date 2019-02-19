//the view model
var HeatMapViewModel = function() {
    self = this;

    self.width = ko.observable(450);
    self.height = ko.observable(300);
    self.radius = ko.observable(200);

    self.min = ko.observable(60);
    self.max = ko.observable(80);

    self.selectedUnit = ko.observable("Fahrenheit");

    self.value1 = ko.observable(Math.floor(Math.random() * 20 + self.min()));
    self.value2 = ko.observable(Math.floor(Math.random() * 20 + self.min()));
    self.value3 = ko.observable(Math.floor(Math.random() * 20 + self.min()));
    self.value4 = ko.observable(Math.floor(Math.random() * 20 + self.min()));
    self.value5 = ko.observable(Math.floor(Math.random() * 20 + self.min()));
    self.value6 = ko.observable(Math.floor(Math.random() * 20 + self.min()));

    //function used when submit button is clicked
    self.submit = function () {
        self.value1(Math.floor(Math.random() * 20 + self.min()));
        self.value2(Math.floor(Math.random() * 20 + self.min()));
        self.value3(Math.floor(Math.random() * 20 + self.min()));
        self.value4(Math.floor(Math.random() * 20 + self.min()));
        self.value5(Math.floor(Math.random() * 20 + self.min()));
        self.value6(Math.floor(Math.random() * 20 + self.min()));
        initialize();
    }

    self.selectedUnit.subscribe(function (newValue, oldValue) {
        if (newValue == "Celsius" && oldValue == "Fahrenheit") {
            self.value1((self.value1() - 32) * 5 / 9);
            self.value2((self.value2() - 32) * 5 / 9);
            self.value3((self.value3() - 32) * 5 / 9);
            self.value4((self.value4() - 32) * 5 / 9);
            self.value5((self.value5() - 32) * 5 / 9);
            self.value6((self.value6() - 32) * 5 / 9);
            self.min(15.56);
            self.max(26.67);
        }
        if (newValue == "Fahrenheit" && oldValue == "Celsius") {
            self.value1(self.value1() * 9 / 5 + 32);
            self.value2(self.value2() * 9 / 5 + 32);
            self.value3(self.value3() * 9 / 5 + 32);
            self.value4(self.value4() * 9 / 5 + 32);
            self.value5(self.value5() * 9 / 5 + 32);
            self.value6(self.value6() * 9 / 5 + 32);
            self.min(60);
            self.max(80);
        }
    });

    self.value1.subscribe(function (newValue) {
        if (newValue > self.max())
            self.value1(self.max());
        if (newValue < self.min())
            self.value1(self.min());
        if (newValue >= self.min() && newValue <= self.max())
            initialize();
    });

    self.value2.subscribe(function (newValue) {
        if (newValue > self.max())
            self.value2(self.max());
        if (newValue < self.min())
            self.value2(self.min());
        if (newValue >= self.min() && newValue <= self.max())
            initialize();
    });

    self.value3.subscribe(function (newValue) {
        if (newValue > self.max())
            self.value3(self.max());
        if (newValue < self.min())
            self.value3(self.min());
        if (newValue >= self.min() && newValue <= self.max())
            initialize();
    });

    self.value4.subscribe(function (newValue) {
        if (newValue > self.max())
            self.value4(self.max());
        if (newValue < self.min())
            self.value4(self.min());
        if (newValue >= self.min() && newValue <= self.max())
            initialize();
    });

    self.value5.subscribe(function (newValue) {
        if (newValue > self.max())
            self.value5(self.max());
        if (newValue < self.min())
            self.value5(self.min());
        if (newValue >= self.min() && newValue <= self.max())
            initialize();
    });

    self.value6.subscribe(function (newValue) {
        if (newValue > self.max())
            self.value1(self.max());
        if (newValue < self.min())
            self.value1(self.min());
        if (newValue >= self.min() && newValue <= self.max())
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
            min: self.min(),
            max: self.max(),
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
