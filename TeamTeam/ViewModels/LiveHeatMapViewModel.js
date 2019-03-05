//the view model
var LiveHeatMapViewModel = function() {
    self = this;

    //time variables
    self.year = ko.observable('');
    self.month = ko.observable('');
    self.day = ko.observable('');
    self.hour = ko.observable('');
    self.minute = ko.observable('');
    self.second = ko.observable('');

    self.width = ko.observable(1024);
    self.height = ko.observable(720);
    self.radius = ko.observable(400);

    self.url = ko.observable();

    self.min = ko.observable(60);
    self.max = ko.observable(85);

    self.result = ko.observable();

    //node values
    self.tempNode1 = ko.observable(0);
    self.tempNode2 = ko.observable(0);
    self.tempNode3 = ko.observable(0);
    self.tempNode4 = ko.observable(0);
    self.tempNode5 = ko.observable(0);
    self.tempNode6 = ko.observable(0);

    self.selectedUnit = ko.observable("Fahrenheit");

    self.selectedUnit.subscribe(function (newValue) {
        if (newValue == "Celsius") {
            self.min(15.56);
            self.max(29.4);
            self.tempNode1(Math.round((self.tempNode1() - 32) * 5 / 9 * 100) / 100);
            self.tempNode2(Math.round((self.tempNode2() - 32) * 5 / 9 * 100) / 100);
            self.tempNode3(Math.round((self.tempNode3() - 32) * 5 / 9 * 100) / 100);
            self.tempNode4(Math.round((self.tempNode4() - 32) * 5 / 9 * 100) / 100);
            self.tempNode5(Math.round((self.tempNode5() - 32) * 5 / 9 * 100) / 100);
            self.tempNode6(Math.round((self.tempNode6() - 32) * 5 / 9 * 100) / 100);
        }
        if (newValue == "Fahrenheit") {
            self.min(60);
            self.max(85);
            self.tempNode1(Math.round((self.tempNode1() * 9 / 5 + 32) * 100) / 100);
            self.tempNode2(Math.round((self.tempNode2() * 9 / 5 + 32) * 100) / 100);
            self.tempNode3(Math.round((self.tempNode3() * 9 / 5 + 32) * 100) / 100);
            self.tempNode4(Math.round((self.tempNode4() * 9 / 5 + 32) * 100) / 100);
            self.tempNode5(Math.round((self.tempNode5() * 9 / 5 + 32) * 100) / 100);
            self.tempNode6(Math.round((self.tempNode6() * 9 / 5 + 32) * 100) / 100);
        }
    });
    
    self.tempNode1.subscribe(function (newValue) {
        initialize();
    });

    self.tempNode2.subscribe(function (newValue) {
        initialize();
    });

    self.tempNode3.subscribe(function (newValue) {
        initialize();
    });

    self.tempNode4.subscribe(function (newValue) {
         initialize();
    });

    self.tempNode5.subscribe(function (newValue) {
        initialize();
    });

    self.tempNode6.subscribe(function (newValue) {
          initialize();
    });
    

    self.searchTemp = function () {
        var currentDate = new Date();
        currentDate.setSeconds(currentDate.getSeconds() - 60);

        self.year(currentDate.getUTCFullYear());
        self.month(currentDate.getUTCMonth()+1);
        self.day(currentDate.getUTCDate());
        self.hour(currentDate.getUTCHours());
        self.minute(currentDate.getUTCMinutes());
        self.second(currentDate.getUTCSeconds());


        if (self.second().toString().length == 1)
            self.second('0' + self.second());

        if (self.minute().toString().length == 1)
            self.minute('0' + self.minute());

        if (self.hour().toString().length == 1)
            self.hour('0' + self.hour());

        if (self.day().toString().length == 1)
            self.day('0' + self.day());

        if (self.month().toString().length == 1)
            self.month('0' + self.month());

        self.url('https://influx.roomtemp.net:8086/query?db=servicedashboard&q=SELECT+tempc+FROM+temperature,host+WHERE+time+%3E+' + 
            '%27' +
            self.year().toString() +
            '-' +
            self.month().toString() +
            '-' +
            self.day().toString() + //date
            'T' +
            self.hour().toString() + //hour
            '%3A' +
            self.minute().toString() + //minute
            '%3A' +
            self.second().toString() +
            '.000000000Z%27+GROUP+BY+host');

        $.ajax({
            url: self.url(),
            dataType: "json",
            success: function (data) {
                self.result(data);
                //self.temp(self.result().results[0].series[0].values[0][1]);

                if (self.selectedUnit() == "Celsius") {
                    for (i = 0; i < self.result().results[0].series.length; i++) {
                        if (self.result().results[0].series[i].tags.host == 'tempNode1')
                            self.tempNode1(self.result().results[0].series[i].values[0][1])
                        if (self.result().results[0].series[i].tags.host == 'tempNode2')
                            self.tempNode2(self.result().results[0].series[i].values[0][1])
                        if (self.result().results[0].series[i].tags.host == 'tempNode3')
                            self.tempNode3(self.result().results[0].series[i].values[0][1])
                        if (self.result().results[0].series[i].tags.host == 'tempNode4')
                            self.tempNode4(self.result().results[0].series[i].values[0][1])
                        if (self.result().results[0].series[i].tags.host == 'tempNode5')
                            self.tempNode5(self.result().results[0].series[i].values[0][1])
                        if (self.result().results[0].series[i].tags.host == 'tempNode6')
                            self.tempNode6(self.result().results[0].series[i].values[0][1])
                    }
                }
                else {
                    for (i = 0; i < self.result().results[0].series.length; i++) {
                        if (self.result().results[0].series[i].tags.host == 'tempNode1')
                            self.tempNode1(Math.round((self.result().results[0].series[i].values[0][1] * 9 / 5 + 32) * 100) / 100)
                        if (self.result().results[0].series[i].tags.host == 'tempNode2')
                            self.tempNode2(Math.round((self.result().results[0].series[i].values[0][1] * 9 / 5 + 32) * 100) / 100)
                        if (self.result().results[0].series[i].tags.host == 'tempNode3')
                            self.tempNode3(Math.round((self.result().results[0].series[i].values[0][1] * 9 / 5 + 32) * 100) / 100)
                        if (self.result().results[0].series[i].tags.host == 'tempNode4')
                            self.tempNode4(Math.round((self.result().results[0].series[i].values[0][1] * 9 / 5 + 32) * 100) / 100)
                        if (self.result().results[0].series[i].tags.host == 'tempNode5')
                            self.tempNode5(Math.round((self.result().results[0].series[i].values[0][1] * 9 / 5 + 32) * 100) / 100)
                        if (self.result().results[0].series[i].tags.host == 'tempNode6')
                            self.tempNode6(Math.round((self.result().results[0].series[i].values[0][1] * 9 / 5 + 32) * 100) / 100)
                    }
                }

                },
            error: function (data) {
                self.result(data);
            }
        });

        setTimeout(self.searchTemp, 5000);
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
                    value: self.tempNode1(),
                    radius: self.radius()

                },
                {//Node 2
                    x: self.width() / 2,
                    y: self.height() / 4,
                    value: self.tempNode2(),
                    radius: self.radius()
                },
                {//Node 3
                    x: self.width() / 8 * 7,
                    y: self.height() / 4,
                    value: self.tempNode3(),
                    radius: self.radius()
                },
                {//Node 4
                    x: self.width() / 8,
                    y: self.height() / 4 * 3,
                    value: self.tempNode4(),
                    radius: self.radius()
                },
                {//Node 5
                    x: self.width() / 2,
                    y: self.height() / 4 * 3,
                    value: self.tempNode5(),
                    radius: self.radius()
                },
                {//Node 6
                    x: self.width() / 8 * 7,
                    y: self.height() / 4 * 3,
                    value: self.tempNode6(),
                    radius: self.radius()
                }
            ]
        });
        self.searchTemp();
    }

    initialize();
};

//binding the viewmodel to the view
ko.applyBindings(new LiveHeatMapViewModel());
