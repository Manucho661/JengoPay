<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../plugins/popper/popper.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Core Scripts -->
<script src="../dist/js/adminlte.js"></script>
<!-- Core Scripts dashboard demo (This is only for demo purposes) -->
<script src="../dist/js/pages/dashboard3.js"></script>
<!-- DataTables  & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Summernote -->
<script src="summernote/summernote-bs4.min.js"></script>

<!-- Advanced Form Features Scripts -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="../plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="../plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="../plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="../plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="../plugins/dropzone/min/dropzone.min.js"></script>


<!-- Core Scripts for demo purposes -->
<script src="../dist/js/demo.js"></script>
<!-- ChartJS -->
<script src="../plugins/chart.js/Chart.min.js"></script>
<!-- ChartJS -->
<script src="../plugins/chart.js/npm_chart.js"></script>
<!-- Datatables Specific Table -->
<script>
    $(function() {
        $("#dataTable").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#dataTable_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>

<!-- Donut Chart Showing Occupancy Rate -->
<script>
    var xValues = ["The Mansion", "Westside Rentals", "Sharon Apartments", "The Mascriot", "Villa Rosa Kempiski"];
    var yValues = [55, 49, 44, 24, 15];
    var barColors = [
        "#192E3E",
        "#E6AD00",
        "#0078D7",
        "#1E7F35",
        "#DC3545"
    ];

    new Chart("occupancyRate", {
        type: "doughnut",
        data: {
            labels: xValues,
            datasets: [{
                backgroundColor: barColors,
                data: yValues
            }]
        },
        options: {
            title: {
                display: true,
                text: "Occupancy Rate"
            }
        }
    });
</script>

<!-- Line Chart Showing Rental Trends basing on the housing units -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let ctx = document.getElementById('myChartRentalTrends')
        .getContext('2d');
        // Initial data
        let initialData = {
            labels: ['January', 'February',
                     'March', 'April', 'May', 'June', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                    ],
            datasets: [{
                label: 'Bedsitters',
                data: [35, 32, 31, 32, 30, 32, 30, 30, 31, 29, 30, 31],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2
            },
                       {
                           label: 'Apartments',
                           data: [20, 18, 15, 20, 17, 19, 17, 18, 19, 17, 16, 19],
                           backgroundColor: 'rgba(14, 132, 12)',
                           borderColor: 'rgba(14, 132, 12)',
                           borderWidth: 2
                       },
                       {
                           label: 'The Mansion',

                           data: [20, 26, 20, 18, 22, 25, 27, 31, 29, 27, 30],
                           backgroundColor: 'rgba(0, 23, 41)',
                           borderColor: 'rgba(0, 23, 41)',
                           borderWidth: 2
                       },
                       {
                           label: 'Villa Rosa Kempiski',
                           data: [29, 18, 17, 26, 24, 23, 25, 25, 29, 27, 26, 25],
                           backgroundColor: 'rgba(192, 0, 1)',
                           borderColor: 'rgba(192, 0, 1)',
                           borderWidth: 2
                       }

                      ]
        };

        let myChartRentalTrends = new Chart(ctx, {
            type: 'line',
            data: initialData,
            options: {
                legend: {
                    display: true,
                    position: 'top',
                    onClick: function(e, legendItem) {
                        // Generate random data for each dataset
                        myChartRentalTrends.data.datasets.forEach(function(dataset) {
                            dataset.data = Array.from({
                                length: initialdata.labels.length
                            },
                                                      () => Math.floor(Math.random() * 100));
                        });

                        // Update the chart
                        myChartRentalTrends.update();

                        // Prevent the default legend behavior (toggle visibility)
                        e.stopPropagation();
                    }
                }
            }
        });
    });
</script>

<!-- Single Property Description Summernote -->
<script>
    $(function() {
        // Summernote
        $('#singlePropertyDescription').summernote()

        // CodeMirror
        CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
            mode: "htmlmixed",
            theme: "monokai"
        });
    })
</script>


<!-- Single Property Rental Agreement Set Up -->
<script>
    $(function() {
        // Summernote
        $('#singleRentalAgreement').summernote()

        // CodeMirror
        CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
            mode: "htmlmixed",
            theme: "monokai"
        });
    })
</script>
<!-- Property Managers DOM -->
<script>
    const individualAsPropertyManagerSection = document.getElementById('individualAsPropertyManager');
    const companyAsManagerDetailsSection = document.getElementById('companyAsManagerDetails') ;

    function showCompanyAsManager() {
        individualAsPropertyManagerSection.style.display = 'none';
        companyAsManagerDetailsSection.style.display = 'block';
    }
    function showIndividualAsManager() {
        companyAsManagerDetailsSection.style.display = 'none';
        individualAsPropertyManagerSection.style.display = 'block';
    }
</script>
<!-- Multi-Rooms DOM -->
<script>
    //Multi-rooms Registration DOM manipulation
    var inBuildingCardDIsplay = document.getElementById('inBuildingCard');
    function selectToShowAndLinkToBuilding() {
        inBuildingCardDIsplay.style.display = 'block';
    }
    function selectToHideAndLinkToBuilding() {
        inBuildingCardDIsplay.style.display = 'none';
    }
    var depositAmountBoxSection = document.getElementById('depositAmountBox');
    function displayDepositAmountRequired() {
        depositAmountBoxSection.style.display = 'block';
    }
    function hideDepositAmountRequired() {
        depositAmountBoxSection.style.display = 'none';
    }
</script>

<!-- Building Rental Payment Option DOM -->
<script>
    $(document).ready(function() {
        $('input[type="radio"]').click(function() {
            var inputValue = $(this).attr("value");
            var targetBox = $("." + inputValue);
            $(".box").not(targetBox).hide();
            $(targetBox).show();
        });
    });
</script>
<!-- Display and Hide Help Form -->
<script>
    function _0x5efa() {
        var _0x360384 = ['1988308MFbCZz', 'none', '3wcLRTl', 'block', '2240820AAihcU', '6IOVELk', '260xlsIjR', '89514jaxpsu', 'display', '1199741mMklLN', 'helpForm', '2760142vOGmXu', '5974905XuzUyx', 'getElementById', '4639024sboHuE', 'style'];
        _0x5efa = function() {
            return _0x360384;
        };
        return _0x5efa();
    }

    function _0x45bf(_0x48d617, _0x46c84c) {
        var _0x5efa9d = _0x5efa();
        return _0x45bf = function(_0x45bf21, _0x15c33f) {
            _0x45bf21 = _0x45bf21 - 0x132;
            var _0x16b4de = _0x5efa9d[_0x45bf21];
            return _0x16b4de;
        }, _0x45bf(_0x48d617, _0x46c84c);
    }(function(_0x5cfa46, _0x4efa4c) {
        var _0x338209 = _0x45bf,
            _0x686bd6 = _0x5cfa46();
        while (!![]) {
            try {
                var _0x4d98b7 = -parseInt(_0x338209(0x13b)) / 0x1 + parseInt(_0x338209(0x136)) / 0x2 * (-parseInt(_0x338209(0x134)) / 0x3) + parseInt(_0x338209(0x132)) / 0x4 + -parseInt(_0x338209(0x13e)) / 0x5 * (-parseInt(_0x338209(0x137)) / 0x6) + parseInt(_0x338209(0x13d)) / 0x7 + parseInt(_0x338209(0x140)) / 0x8 + -parseInt(_0x338209(0x139)) / 0x9 * (-parseInt(_0x338209(0x138)) / 0xa);
                if (_0x4d98b7 === _0x4efa4c) break;
                else _0x686bd6['push'](_0x686bd6['shift']());
            } catch (_0x207019) {
                _0x686bd6['push'](_0x686bd6['shift']());
            }
        }
    }(_0x5efa, 0x93a0f));

    function openForm() {
        var _0x3f0a9d = _0x45bf;
        document[_0x3f0a9d(0x13f)](_0x3f0a9d(0x13c))['style'][_0x3f0a9d(0x13a)] = _0x3f0a9d(0x135);
    }

    function closeForm() {
        var _0x2f9423 = _0x45bf;
        document[_0x2f9423(0x13f)](_0x2f9423(0x13c))[_0x2f9423(0x141)]['display'] = _0x2f9423(0x133);
    }
</script>


<!-- Building Details Icon Toogler -->
<script>
    $(document).ready(function() {
        // Add minus icon for collapse element which is open by default
        $(".collapse.show").each(function() {
            $(this).siblings(".card-header").find(".btn i").html("-");
        });

        // Toggle plus minus icon on show hide of collapse element
        $(".collapse").on('show.bs.collapse', function() {
            $(this).parent().find(".card-header .btn i").html("-");
        }).on('hide.bs.collapse', function() {
            $(this).parent().find(".card-header .btn i").html("+");
        });
    });
</script>

<!-- Show Building Owners Fields DOM -->
<script>
    //Show Individual Building Owner
    function _0x36dd() {
        var _0x297681 = ['block', '250JoGcJc', '5948600VuKOnm', 'Last\x20Name\x20Required\x20Before\x20you\x20Close', 'none', '4414LEGmEU', '4686780xABWVD', '50860PStEag', '295FDnylZ', '4476042GJSquo', '#lastName', 'Phone\x20Number\x20Required\x20before\x20you\x20Close', '3749625pGSygv', 'Owner\x20Email\x20Required\x20before\x20you\x20Close', '38676ANLuap', 'style', '#individualCloseBtn', 'Last\x20Name\x20Last\x20Name\x20can\x27t\x20be\x20the\x20same\x20as\x20First\x20Name', 'display', '#phoneNumber', 'val', 'click'];
        _0x36dd = function() {
            return _0x297681;
        };
        return _0x36dd();
    }(function(_0x27219b, _0x3bf112) {
        var _0x1b014f = _0x37d4,
            _0x2aea38 = _0x27219b();
        while (!![]) {
            try {
                var _0x2ccf21 = -parseInt(_0x1b014f(0x6e)) / 0x1 * (parseInt(_0x1b014f(0x81)) / 0x2) + parseInt(_0x1b014f(0x74)) / 0x3 + -parseInt(_0x1b014f(0x6d)) / 0x4 * (-parseInt(_0x1b014f(0x7d)) / 0x5) + parseInt(_0x1b014f(0x6f)) / 0x6 + -parseInt(_0x1b014f(0x82)) / 0x7 + parseInt(_0x1b014f(0x7e)) / 0x8 + -parseInt(_0x1b014f(0x72)) / 0x9;
                if (_0x2ccf21 === _0x3bf112) break;
                else _0x2aea38['push'](_0x2aea38['shift']());
            } catch (_0x1a8e11) {
                _0x2aea38['push'](_0x2aea38['shift']());
            }
        }
    }(_0x36dd, 0x61e62));

    function _0x37d4(_0x33f868, _0x3f4260) {
        var _0x36dd4c = _0x36dd();
        return _0x37d4 = function(_0x37d4f6, _0x1d7341) {
            _0x37d4f6 = _0x37d4f6 - 0x6d;
            var _0x143ff0 = _0x36dd4c[_0x37d4f6];
            return _0x143ff0;
        }, _0x37d4(_0x33f868, _0x3f4260);
    }

    function showIndividualOwner() {
        var _0x39227a = _0x37d4,
            _0x1ce2d4 = document['getElementById']('individualInfoDiv');
        individualInfoDiv[_0x39227a(0x75)]['display'] = _0x39227a(0x7c), entityInfoDiv[_0x39227a(0x75)][_0x39227a(0x78)] = _0x39227a(0x80), $(_0x39227a(0x76))[_0x39227a(0x7b)](function(_0x551cb9) {
            var _0x458d9a = _0x39227a;
            _0x551cb9['preventDefault']();
            if ($('#firstName')[_0x458d9a(0x7a)]() == '') return alert('First\x20Name\x20Required\x20Before\x20you\x20Close'), ![];
            else {
                if ($(_0x458d9a(0x70))['val']() == '') return alert(_0x458d9a(0x7f)), ![];
                else {
                    if ($(_0x458d9a(0x70))[_0x458d9a(0x7a)]() == $('#firstName')['val']()) return alert(_0x458d9a(0x77)), ![];
                    else {
                        if ($(_0x458d9a(0x79))[_0x458d9a(0x7a)]() == '') return alert(_0x458d9a(0x71)), ![];
                        else {
                            if ($('#ownerEmail')[_0x458d9a(0x7a)]() == '') return alert(_0x458d9a(0x73)), ![];
                            else individualInfoDiv[_0x458d9a(0x75)]['display'] = _0x458d9a(0x80);
                        }
                    }
                }
            }
        });
    }

    //Show Entity as the Building Owner
    (function(_0x248a5e, _0x2727e2) {
        var _0x17728a = _0x5035,
            _0x2894f8 = _0x248a5e();
        while (!![]) {
            try {
                var _0x25f3ba = parseInt(_0x17728a(0xb9)) / 0x1 * (-parseInt(_0x17728a(0xc3)) / 0x2) + parseInt(_0x17728a(0xaf)) / 0x3 + parseInt(_0x17728a(0xc1)) / 0x4 + parseInt(_0x17728a(0xb4)) / 0x5 * (-parseInt(_0x17728a(0xbe)) / 0x6) + -parseInt(_0x17728a(0xb3)) / 0x7 + parseInt(_0x17728a(0xb1)) / 0x8 * (-parseInt(_0x17728a(0xc0)) / 0x9) + -parseInt(_0x17728a(0xc6)) / 0xa * (-parseInt(_0x17728a(0xc2)) / 0xb);
                if (_0x25f3ba === _0x2727e2) break;
                else _0x2894f8['push'](_0x2894f8['shift']());
            } catch (_0x3259f) {
                _0x2894f8['push'](_0x2894f8['shift']());
            }
        }
    }(_0x5cef, 0x27563));

    function _0x5035(_0x137470, _0x2029d9) {
        var _0x5cefd4 = _0x5cef();
        return _0x5035 = function(_0x5035f1, _0x13c67d) {
            _0x5035f1 = _0x5035f1 - 0xae;
            var _0x528b8f = _0x5cefd4[_0x5035f1];
            return _0x528b8f;
        }, _0x5035(_0x137470, _0x2029d9);
    }

    function _0x5cef() {
        var _0x1e8c55 = ['val', '#entityRepRole', 'Entity\x20Representative\x20Role\x20Required', '490008NxgpPG', 'style', '8eOyUJB', '#entityRepresentative', '199570ujpDry', '102535ZYWBin', 'display', '#entityEmail', '#entityName', 'preventDefault', '32914mYDHti', 'Entity\x20Representative\x20Required', 'getElementById', 'click', 'entityInfoDiv', '84ZwtMuv', 'Entity\x20Email\x20Required\x20before\x20you\x20Close', '300249eFTGAF', '17236EznKiF', '641575xHySxy', '4bYxigF', 'block', 'Entity\x20Name\x20Required\x20before\x20you\x20Close', '70FgpscT'];
        _0x5cef = function() {
            return _0x1e8c55;
        };
        return _0x5cef();
    }

    function showEntityOwner() {
        var _0x543ff9 = _0x5035,
            _0x398092 = document[_0x543ff9(0xbb)](_0x543ff9(0xbd));
        entityInfoDiv[_0x543ff9(0xb0)][_0x543ff9(0xb5)] = _0x543ff9(0xc4), individualInfoDiv[_0x543ff9(0xb0)][_0x543ff9(0xb5)] = 'none', $('#entityCloseDivBtn')[_0x543ff9(0xbc)](function(_0x281c02) {
            var _0x1eebcc = _0x543ff9;
            _0x281c02[_0x1eebcc(0xb8)]();
            if ($(_0x1eebcc(0xb7))[_0x1eebcc(0xc7)]() == '') return alert(_0x1eebcc(0xc5)), ![];
            else {
                if ($('#entityPhone')[_0x1eebcc(0xc7)]() == '') return alert('Entity\x20Phone\x20Number\x20Required\x20before\x20you\x20Close'), ![];
                else {
                    if ($(_0x1eebcc(0xb6))[_0x1eebcc(0xc7)]() == '') return alert(_0x1eebcc(0xbf)), 0x0;
                    else {
                        if ($(_0x1eebcc(0xb2))['val']() == '') return alert(_0x1eebcc(0xba)), ![];
                        else {
                            if ($(_0x1eebcc(0xc8))[_0x1eebcc(0xc7)]() == '') return alert(_0x1eebcc(0xae)), ![];
                            else entityInfoDiv[_0x1eebcc(0xb0)][_0x1eebcc(0xb5)] = 'none';
                        }
                    }
                }
            }
        });
    }
</script>

<!-- Specify Solar Avilability DOM -->
<script>
    function _0x46b7() {
        var _0x4d7a85 = ['#installationCompany', '1268184PtZbpo', '6WXLSBR', '3617070ViYaqI', 'style', '#solarPrimaryUse', 'click', 'Solar\x20Brand\x20Name\x20Required\x20before\x20you\x20Close', '#solarBrand', 'getElementById', '#closeSolarProviderBtn', 'none', 'specifySolarPrivider', '63ItAneg', '5tMnpQg', 'display', '669104XTYtnu', 'val', 'Please\x20Specify\x20the\x20Number\x20of\x20Panels\x20Before\x20you\x20Close', '3939008hxyIIU', '4167016KTLqpW', '783772GrtLiz', '#noOfPanels', 'Specify\x20the\x20Primary\x20Use\x20of\x20the\x20Solar\x20Panels\x20before\x20you\x20Close', '1369736CtNyTD', 'preventDefault'];
        _0x46b7 = function() {
            return _0x4d7a85;
        };
        return _0x46b7();
    }
    var _0x304f93 = _0x4710;
    (function(_0x3dc99b, _0xb5c8d9) {
        var _0xbc71ec = _0x4710,
            _0x1db688 = _0x3dc99b();
        while (!![]) {
            try {
                var _0x42097f = -parseInt(_0xbc71ec(0xa8)) / 0x1 + parseInt(_0xbc71ec(0xad)) / 0x2 + -parseInt(_0xbc71ec(0xb3)) / 0x3 + -parseInt(_0xbc71ec(0xb0)) / 0x4 * (parseInt(_0xbc71ec(0xa6)) / 0x5) + -parseInt(_0xbc71ec(0xb4)) / 0x6 * (parseInt(_0xbc71ec(0xac)) / 0x7) + -parseInt(_0xbc71ec(0xab)) / 0x8 + -parseInt(_0xbc71ec(0xa5)) / 0x9 * (-parseInt(_0xbc71ec(0xb5)) / 0xa);
                if (_0x42097f === _0xb5c8d9) break;
                else _0x1db688['push'](_0x1db688['shift']());
            } catch (_0x2fdad5) {
                _0x1db688['push'](_0x1db688['shift']());
            }
        }
    }(_0x46b7, 0x621f1));
    var specifySolarPrividerSection = document[_0x304f93(0xbb)](_0x304f93(0xa4));

    function _0x4710(_0x58997d, _0x4fa89b) {
        var _0x46b7f9 = _0x46b7();
        return _0x4710 = function(_0x4710ae, _0xbf840a) {
            _0x4710ae = _0x4710ae - 0xa4;
            var _0x11285b = _0x46b7f9[_0x4710ae];
            return _0x11285b;
        }, _0x4710(_0x58997d, _0x4fa89b);
    }

    function specifySolarProvider() {
        var _0x586ee8 = _0x304f93;
        specifySolarPrivider[_0x586ee8(0xb6)]['display'] = 'block', $(_0x586ee8(0xbc))[_0x586ee8(0xb8)](function(_0x1cbd86) {
            var _0x8954b9 = _0x586ee8;
            _0x1cbd86[_0x8954b9(0xb1)]();
            if ($(_0x8954b9(0xba))['val']() == '') return alert(_0x8954b9(0xb9)), ![];
            else {
                if ($(_0x8954b9(0xb2))['val']() == '') return alert('Please\x20Specify\x20Solar\x20Installation\x20Company\x20before\x20you\x20Close'), ![];
                else {
                    if ($(_0x8954b9(0xae))['val']() == '') alert(_0x8954b9(0xaa));
                    else {
                        if ($(_0x8954b9(0xb7))[_0x8954b9(0xa9)]() == '') return alert(_0x8954b9(0xaf)), ![];
                        else specifySolarPrivider[_0x8954b9(0xb6)][_0x8954b9(0xa7)] = 'none';
                    }
                }
            }
        });
    }

    function hideSolarProvider() {
        var _0x19199a = _0x304f93;
        specifySolarPrivider['style'][_0x19199a(0xa7)] = _0x19199a(0xbd);
    }
</script>

<!-- Construction Authority Displays DOM -->
<script>
    //NCA Approval
    var _0x1c618d = _0x6d0b;
    (function(_0x4961e9, _0x5a9f03) {
        var _0x1ea8bc = _0x6d0b,
            _0x145c4b = _0x4961e9();
        while (!![]) {
            try {
                var _0x2c1770 = parseInt(_0x1ea8bc(0x99)) / 0x1 * (-parseInt(_0x1ea8bc(0x9f)) / 0x2) + -parseInt(_0x1ea8bc(0xac)) / 0x3 + parseInt(_0x1ea8bc(0x98)) / 0x4 * (-parseInt(_0x1ea8bc(0x9e)) / 0x5) + -parseInt(_0x1ea8bc(0x9d)) / 0x6 * (-parseInt(_0x1ea8bc(0xad)) / 0x7) + -parseInt(_0x1ea8bc(0xa8)) / 0x8 + -parseInt(_0x1ea8bc(0x9b)) / 0x9 * (-parseInt(_0x1ea8bc(0xab)) / 0xa) + parseInt(_0x1ea8bc(0xa0)) / 0xb * (parseInt(_0x1ea8bc(0xa5)) / 0xc);
                if (_0x2c1770 === _0x5a9f03) break;
                else _0x145c4b['push'](_0x145c4b['shift']());
            } catch (_0x3e24e4) {
                _0x145c4b['push'](_0x145c4b['shift']());
            }
        }
    }(_0x3c16, 0xeedaa));
    var ncaApprivalCardSection = document[_0x1c618d(0x96)](_0x1c618d(0xaf));

    function attachNcaApproval() {
        var _0x3830a0 = _0x1c618d;
        ncaApprivalCardSection[_0x3830a0(0xa9)][_0x3830a0(0xa2)] = _0x3830a0(0x97), $(_0x3830a0(0x9c))[_0x3830a0(0xa7)](function(_0x4abce5) {
            var _0xc8837f = _0x3830a0;
            _0x4abce5[_0xc8837f(0xae)]();
            if ($(_0xc8837f(0xa3))[_0xc8837f(0x9a)]() == '') return alert(_0xc8837f(0xa6)), ![];
            else {
                if ($(_0xc8837f(0xa4))[_0xc8837f(0x9a)]() == '') return alert('Approval\x20Date\x20Required'), ![];
                else {
                    if ($(_0xc8837f(0xaa))[_0xc8837f(0x9a)]() == '') return alert('Approval\x20Copy\x20Required'), ![];
                    else ncaApprivalCardSection['style'][_0xc8837f(0xa2)] = _0xc8837f(0xa1);
                }
            }
        });
    }

    function _0x6d0b(_0x2614bb, _0x1a47ea) {
        var _0x3c16ba = _0x3c16();
        return _0x6d0b = function(_0x6d0bfa, _0x534c83) {
            _0x6d0bfa = _0x6d0bfa - 0x96;
            var _0x498091 = _0x3c16ba[_0x6d0bfa];
            return _0x498091;
        }, _0x6d0b(_0x2614bb, _0x1a47ea);
    }

    function closeAttachNcaApproval() {
        var _0x1df058 = _0x1c618d;
        ncaApprivalCardSection[_0x1df058(0xa9)][_0x1df058(0xa2)] = _0x1df058(0xa1);
    }

    function _0x3c16() {
        var _0x262f43 = ['24xYoPmZ', 'Construction\x20Authority\x20Number\x20Required', 'click', '3016344TBruza', 'style', '#ncaApprovalCopy', '70KfaFVK', '5358915PugGBN', '35SBLlew', 'preventDefault', 'ncaApprivalCard', 'getElementById', 'block', '8WeVnmq', '5ibRhVM', 'val', '483489qHPFNl', '#closeNcaApprovalBtn', '474954qmwmyj', '2928630aBmqSG', '159716tbmaMI', '21673267yvkBqG', 'none', 'display', '#approvalNo', '#approvalDate'];
        _0x3c16 = function() {
            return _0x262f43;
        };
        return _0x3c16();
    }

    //NEMA Approval DOM
    function _0x1cda() {
        var _0x2bcbdc = ['346023KAfBrV', '341664bwPXya', 'val', '#nemaApprovalDate', 'block', '86109gwHfSj', '4PltjcO', 'none', 'NEMA\x20Approval\x20Date\x20Required', 'nemaApprovalSpecify', 'preventDefault', 'display', '2864045tyVyLW', '2873610lwNUEb', '192ikxrtg', 'click', '1882069KeSReU', 'style', '495692VztPJI', '#nemaApprovalNumber', '#closeNemaApproval'];
        _0x1cda = function() {
            return _0x2bcbdc;
        };
        return _0x1cda();
    }
    var _0x2c8272 = _0x2ea2;
    (function(_0x5c2350, _0x17d1b4) {
        var _0x45c317 = _0x2ea2,
            _0x1cccab = _0x5c2350();
        while (!![]) {
            try {
                var _0x5d4b57 = -parseInt(_0x45c317(0xe3)) / 0x1 + parseInt(_0x45c317(0xe8)) / 0x2 * (parseInt(_0x45c317(0xe7)) / 0x3) + parseInt(_0x45c317(0xdf)) / 0x4 + -parseInt(_0x45c317(0xee)) / 0x5 + parseInt(_0x45c317(0xef)) / 0x6 + -parseInt(_0x45c317(0xf2)) / 0x7 + -parseInt(_0x45c317(0xf0)) / 0x8 * (-parseInt(_0x45c317(0xe2)) / 0x9);
                if (_0x5d4b57 === _0x17d1b4) break;
                else _0x1cccab['push'](_0x1cccab['shift']());
            } catch (_0x110420) {
                _0x1cccab['push'](_0x1cccab['shift']());
            }
        }
    }(_0x1cda, 0x61924));
    var nemaApprovalSpecifySection = document['getElementById'](_0x2c8272(0xeb));

    function _0x2ea2(_0x2f2ba9, _0x3dc41f) {
        var _0x1cda2c = _0x1cda();
        return _0x2ea2 = function(_0x2ea2be, _0x1e0963) {
            _0x2ea2be = _0x2ea2be - 0xdf;
            var _0x1ac9d9 = _0x1cda2c[_0x2ea2be];
            return _0x1ac9d9;
        }, _0x2ea2(_0x2f2ba9, _0x3dc41f);
    }

    function nemaApprovalShow() {
        var _0x3e9817 = _0x2c8272;
        nemaApprovalSpecifySection[_0x3e9817(0xf3)][_0x3e9817(0xed)] = _0x3e9817(0xe6), $(_0x3e9817(0xe1))[_0x3e9817(0xf1)](function(_0x41d4df) {
            var _0xec7c9e = _0x3e9817;
            _0x41d4df[_0xec7c9e(0xec)]();
            if ($(_0xec7c9e(0xe0))[_0xec7c9e(0xe4)]() == '') return alert('Nema\x20Approval\x20Number\x20Required'), ![];
            else {
                if ($(_0xec7c9e(0xe5))[_0xec7c9e(0xe4)]() == '') return alert(_0xec7c9e(0xea)), ![];
                else {
                    if ($('#nemaApprovalCopy')[_0xec7c9e(0xe4)]() == '') return alert('NEMA\x20Approval\x20Copy\x20Required'), ![];
                    else nemaApprovalSpecifySection['style'][_0xec7c9e(0xed)] = _0xec7c9e(0xe9);
                }
            }
        });
    }

    function nemaApprovalHide() {
        var _0x1051b7 = _0x2c8272;
        nemaApprovalSpecifySection[_0x1051b7(0xf3)][_0x1051b7(0xed)] = 'none';
    }

    //Local Government Specifications DOM
    var _0x514fd3 = _0x194b;
    (function(_0x143c7b, _0x3e2e5a) {
        var _0x5074ad = _0x194b,
            _0x4afc78 = _0x143c7b();
        while (!![]) {
            try {
                var _0x4128cf = parseInt(_0x5074ad(0x8f)) / 0x1 * (parseInt(_0x5074ad(0x88)) / 0x2) + -parseInt(_0x5074ad(0x80)) / 0x3 + parseInt(_0x5074ad(0x85)) / 0x4 + -parseInt(_0x5074ad(0x81)) / 0x5 * (-parseInt(_0x5074ad(0x92)) / 0x6) + parseInt(_0x5074ad(0x94)) / 0x7 + parseInt(_0x5074ad(0x87)) / 0x8 + -parseInt(_0x5074ad(0x83)) / 0x9 * (parseInt(_0x5074ad(0x96)) / 0xa);
                if (_0x4128cf === _0x3e2e5a) break;
                else _0x4afc78['push'](_0x4afc78['shift']());
            } catch (_0x50a160) {
                _0x4afc78['push'](_0x4afc78['shift']());
            }
        }
    }(_0x5af9, 0x914ea));
    var localGovSpecificationsSection = document[_0x514fd3(0x93)](_0x514fd3(0x84));

    function _0x194b(_0x118b21, _0x3a4f8e) {
        var _0x5af90a = _0x5af9();
        return _0x194b = function(_0x194b18, _0x111a33) {
            _0x194b18 = _0x194b18 - 0x7f;
            var _0x48a932 = _0x5af90a[_0x194b18];
            return _0x48a932;
        }, _0x194b(_0x118b21, _0x3a4f8e);
    }

    function showLocalGovernmentApproval() {
        var _0x56391d = _0x514fd3;
        localGovSpecificationsSection[_0x56391d(0x82)][_0x56391d(0x8b)] = _0x56391d(0x86), $(_0x56391d(0x91))[_0x56391d(0x8c)](function() {
            var _0x4fe001 = _0x56391d;
            if ($(_0x4fe001(0x8a))['val']() == '') return alert(_0x4fe001(0x90)), ![];
            else {
                if ($(_0x4fe001(0x95))[_0x4fe001(0x8e)]() == '') return alert(_0x4fe001(0x8d)), ![];
                else {
                    if ($(_0x4fe001(0x89))[_0x4fe001(0x8e)]() == '') return alert('Local\x20Government\x20Approval\x20Copy\x20Required'), ![];
                    else localGovSpecificationsSection[_0x4fe001(0x82)][_0x4fe001(0x8b)] = _0x4fe001(0x7f);
                }
            }
        });
    }

    function _0x5af9() {
        var _0xbff494 = ['Local\x20Government\x20Approval\x20Number\x20Required', '#closeLocalGovSpecifications', '6MppNfR', 'getElementById', '8094352MNXAtW', '#localGovApprovalDate', '3530fpRMsq', 'none', '159696cLXDtW', '1876370YKDDhM', 'style', '91683thckcI', 'localGovSpecifications', '4370420mhFssb', 'block', '8563352MCOvih', '157082ekJuOe', '#localGovApprovalCopy', '#localGovApprovalNo', 'display', 'click', 'Local\x20Government\x20Approval\x20Date\x20Required', 'val', '7jtmRls'];
        _0x5af9 = function() {
            return _0xbff494;
        };
        return _0x5af9();
    }

    function hideLocalGovernmentApproval() {
        var _0x5da196 = _0x514fd3;
        localGovSpecificationsSection[_0x5da196(0x82)]['display'] = _0x5da196(0x7f);
    }

    //Insurance Policy Scripts
    function _0x21cb(_0x4f2690, _0x32aca6) {
        var _0xc109c6 = _0xc109();
        return _0x21cb = function(_0x21cb18, _0x29df81) {
            _0x21cb18 = _0x21cb18 - 0x9f;
            var _0x144932 = _0xc109c6[_0x21cb18];
            return _0x144932;
        }, _0x21cb(_0x4f2690, _0x32aca6);
    }
    var _0x1e19e0 = _0x21cb;
    (function(_0x4c8f09, _0x38f0d3) {
        var _0x589c90 = _0x21cb,
            _0x113959 = _0x4c8f09();
        while (!![]) {
            try {
                var _0x5f6e02 = parseInt(_0x589c90(0xa0)) / 0x1 + parseInt(_0x589c90(0xac)) / 0x2 + parseInt(_0x589c90(0xa9)) / 0x3 * (parseInt(_0x589c90(0x9f)) / 0x4) + parseInt(_0x589c90(0xa3)) / 0x5 + parseInt(_0x589c90(0xb2)) / 0x6 * (-parseInt(_0x589c90(0xaa)) / 0x7) + parseInt(_0x589c90(0xae)) / 0x8 + parseInt(_0x589c90(0xb0)) / 0x9 * (-parseInt(_0x589c90(0xa6)) / 0xa);
                if (_0x5f6e02 === _0x38f0d3) break;
                else _0x113959['push'](_0x113959['shift']());
            } catch (_0x45d490) {
                _0x113959['push'](_0x113959['shift']());
            }
        }
    }(_0xc109, 0xbad63));

    function _0xc109() {
        var _0x35a3d4 = ['#policy_until_date', '#closeInsuranceInfoBtn', '2804UQEFsX', '1462464OTAVNC', 'Please\x20Specify\x20Policy\x20Expiry\x20Date', 'preventDefault', '7035540xsatDb', 'val', 'Insurance\x20Policy\x20Initial\x20Date\x20Required', '5925290mwjMyt', 'style', 'getElementById', '4839BzIiwv', '7sETDLJ', 'display', '2104552OWzbUa', 'none', '9083640Tkimbi', 'Insurance\x20Policy\x20Provider\x20Required', '63TxLyEZ', '#insurance_provider', '7650180WPUXDo'];
        _0xc109 = function() {
            return _0x35a3d4;
        };
        return _0xc109();
    }
    var specifyInsuranceCoverInfoCardInfo = document[_0x1e19e0(0xa8)]('specifyInsuranceCoverInfoCard');

    function insuranceCoverYes() {
        var _0x34ea4d = _0x1e19e0;
        specifyInsuranceCoverInfoCardInfo[_0x34ea4d(0xa7)][_0x34ea4d(0xab)] = 'block', $(_0x34ea4d(0xb4))['click'](function(_0x3eb00c) {
            var _0x224936 = _0x34ea4d;
            _0x3eb00c[_0x224936(0xa2)]();
            if ($('#insurance_policy')[_0x224936(0xa4)]() == '') return alert('Insurance\x20Policy\x20Provider\x20Required'), ![];
            else {
                if ($(_0x224936(0xb1))['val']() == '') return alert(_0x224936(0xaf)), ![];
                else {
                    if ($('#policy_from_date')[_0x224936(0xa4)]() == '') return alert(_0x224936(0xa5)), ![];
                    else {
                        if ($(_0x224936(0xb3))[_0x224936(0xa4)]() == '') return alert(_0x224936(0xa1)), ![];
                        else specifyInsuranceCoverInfoCardInfo[_0x224936(0xa7)][_0x224936(0xab)] = _0x224936(0xad);
                    }
                }
            }
        });
    }

    function insuranceCoverNo() {
        var _0x2e3351 = _0x1e19e0;
        specifyInsuranceCoverInfoCardInfo[_0x2e3351(0xa7)]['display'] = _0x2e3351(0xad);
    }

    //Deposits DOM
    var depositCardSection = document.getElementById('depositCard');

    function showDepositBox() {
        depositCardSection.style.display = 'block';
    }

    function hideDepositBox() {
        depositCardSection.style.display = 'none';
    }

    //Step by Step Building Registration and Validations DOM -->
    $(document).ready(function() {

        $("#stepOneNextBtn").click(function(e) {
            e.preventDefault();
            $("#sectionTwo").show();
            $("#sectionOne").hide();

            $("#stepOneIndicatorNo").html('<i class="fa fa-check"><i>');
            $("#stepOneIndicatorNo").css('background-color', '#FFC107');
            $("#stepOneIndicatorNo").css('color', '#00192D');
            $("#stepOneIndicatorText").html('Done');
        });

        $("#stepTwoBackBtn").click(function(e) {
            e.preventDefault();
            $("#sectionTwo").hide();
            $("#sectionOne").show();

            $("#stepOneIndicatorNo").html('1');
            $("#stepOneIndicatorNo").css('background-color', '#00192D');
            $("#stepOneIndicatorNo").css('color', '#FFC107');
            $("#stepOneIndicatorText").html('Overview');
        });

        $("#stepTwoNextBtn").click(function(e) {
            e.preventDefault();
            $("#sectionTwo").hide();
            $("#sectionThree").show();

            $("#stepTwoIndicatorNo").html('<i class="fa fa-check"><i>');
            $("#stepTwoIndicatorNo").css('background-color', '#FFC107');
            $("#stepTwoIndicatorNo").css('color', '#00192D');
            $("#stepTwoIndicatorText").html('Done');
        });

        $("#stepThreeBackBtn").click(function(e) {
            e.preventDefault();
            $("#sectionTwo").show();
            $("#sectionThree").hide();

            $("#stepTwoIndicatorNo").html('2');
            $("#stepTwoIndicatorNo").css('background-color', '#00192D');
            $("#stepTwoIndicatorNo").css('color', '#FFC107');
            $("#stepTwoIndicatorText").html('Identification');
        });

        $("#stepThreeNextBtn").click(function(e) {
            e.preventDefault();
            $("#sectionThree").hide();
            $("#sectionFour").show();

            $("#stepThreeIndicatorNo").html('<i class="fa fa-check"><i>');
            $("#stepThreeIndicatorNo").css('background-color', '#FFC107');
            $("#stepThreeIndicatorNo").css('color', '#00192D');
            $("#stepThreeIndicatorText").html('Done');
        });

        $("#stepFourBackBtn").click(function(e) {
            e.preventDefault();
            $("#sectionThree").show();
            $("#sectionFour").hide();

            $("#stepThreeIndicatorNo").html('3');
            $("#stepThreeIndicatorNo").css('background-color', '#00192D');
            $("#stepThreeIndicatorNo").css('color', '#FFC107');
            $("#stepThreeIndicatorText").html('Ownership');
        });

        $("#stepFourNextBtn").click(function(e) {
            e.preventDefault();
            $("#sectionFour").hide();
            $("#sectionFive").show();

            $("#stepFourIndicatorNo").html('<i class="fa fa-check"><i>');
            $("#stepFourIndicatorNo").css('background-color', '#FFC107');
            $("#stepFourIndicatorNo").css('color', '#00192D');
            $("#stepFourIndicatorText").html('Done');
        });

        $("#stepFiveBackBtn").click(function(e) {
            e.preventDefault();
            $("#sectionFour").show();
            $("#sectionFive").hide();

            $("#stepFourIndicatorNo").html('4');
            $("#stepFourIndicatorNo").css('background-color', '#00192D');
            $("#stepFourIndicatorNo").css('color', '#FFC107');
            $("#stepFourIndicatorText").html('Utilities');
        });

        $("#stepFiveNextBtn").click(function(e) {
            e.preventDefault();
            $("#sectionFive").hide();
            $("#sectionSix").show();

            $("#stepFiveIndicatorNo").html('<i class="fa fa-check"><i>');
            $("#stepFiveIndicatorNo").css('background-color', '#FFC107');
            $("#stepFiveIndicatorNo").css('color', '#00192D');
            $("#stepFiveIndicatorText").html('Done');
        });

        $("#stepSixBackBtn").click(function(e) {
            e.preventDefault();
            $("#sectionFive").show();
            $("#sectionSix").hide();

            $("#stepFiveIndicatorNo").html('5');
            $("#stepFiveIndicatorNo").css('background-color', '#00192D');
            $("#stepFiveIndicatorNo").css('color', '#FFC107');
            $("#stepFiveIndicatorText").html('Regulations');
        });

        $("#stepSixNextBtn").click(function(e) {
            e.preventDefault();
            $("#sectionSix").hide();
            $("#sectionSeven").show();

            $("#stepSixIndicatorNo").html('<i class="fa fa-check"><i>');
            $("#stepSixIndicatorNo").css('background-color', '#FFC107');
            $("#stepSixIndicatorNo").css('color', '#00192D');
            $("#stepSixIndicatorText").html('Done');
        });

        $("#stepSevenBackBtn").click(function(e) {
            e.preventDefault();
            $("#sectionSix").show();
            $("#sectionSeven").hide();

            $("#stepSixIndicatorNo").html('6');
            $("#stepSixIndicatorNo").css('background-color', '#00192D');
            $("#stepSixIndicatorNo").css('color', '#FFC107');
            $("#stepSixIndicatorText").html('Insurance');
        });

        $("#stepSevenNextBtn").click(function(e) {
            e.preventDefault();
            $("#sectionSeven").hide();
            $("#sectionEight").show();

            $("#stepSevenIndicatorNo").html('<i class="fa fa-check"><i>');
            $("#stepSevenIndicatorNo").css('background-color', '#FFC107');
            $("#stepSevenIndicatorNo").css('color', '#00192D');
            $("#stepSevenIndicatorText").html('Done');
        });

        $("#stepEightBackBtn").click(function(e) {
            e.preventDefault();
            $("#sectionSeven").show();
            $("#sectionEight").hide();

            $("#stepSevenIndicatorNo").html('7');
            $("#stepSevenIndicatorNo").css('background-color', '#00192D');
            $("#stepSevenIndicatorNo").css('color', '#FFC107');
            $("#stepSevenIndicatorText").html('Photos');
        });

    });
</script>

<!-- Form Selector Scripts -->
<script>
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        //Datemask dd/mm/yyyy
        $('#datemask').inputmask('dd/mm/yyyy', {
            'placeholder': 'dd/mm/yyyy'
        })
        //Datemask2 mm/dd/yyyy
        $('#datemask2').inputmask('mm/dd/yyyy', {
            'placeholder': 'mm/dd/yyyy'
        })
        //Money Euro
        $('[data-mask]').inputmask()

        //Date picker
        $('#reservationdate').datetimepicker({
            format: 'L'
        });

        //Date and time picker
        $('#reservationdatetime').datetimepicker({
            icons: {
                time: 'far fa-clock'
            }
        });

        //Date range picker
        $('#reservation').daterangepicker()
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({
            timePicker: true,
            timePickerIncrement: 30,
            locale: {
                format: 'MM/DD/YYYY hh:mm A'
            }
        })
        //Date range as a button
        $('#daterange-btn').daterangepicker({
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                               .endOf(
                                   'month')
                              ]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
        },
                                            function(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        }
                                           )

        //Timepicker
        $('#timepicker').datetimepicker({
            format: 'LT'
        })

        //Bootstrap Duallistbox
        $('.duallistbox').bootstrapDualListbox()

        //Colorpicker
        $('.my-colorpicker1').colorpicker()
        //color picker with addon
        $('.my-colorpicker2').colorpicker()

        $('.my-colorpicker2').on('colorpickerChange', function(event) {
            $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
        })

        $("input[data-bootstrap-switch]").each(function() {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        })

    })
    // BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function() {
        window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    })

    // DropzoneJS Demo Code Start
    Dropzone.autoDiscover = false

    // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
    var previewNode = document.querySelector("#template")
    previewNode.id = ""
    var previewTemplate = previewNode.parentNode.innerHTML
    previewNode.parentNode.removeChild(previewNode)

    var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
        url: "/target-url", // Set the url
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        parallelUploads: 20,
        previewTemplate: previewTemplate,
        autoQueue: false, // Make sure the files aren't queued until manually added
        previewsContainer: "#previews", // Define the container to display the previews
        clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
    })

    myDropzone.on("addedfile", function(file) {
        // Hookup the start button
        file.previewElement.querySelector(".start").onclick = function() {
            myDropzone.enqueueFile(file)
        }
    })

    // Update the total progress bar
    myDropzone.on("totaluploadprogress", function(progress) {
        document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
    })

    myDropzone.on("sending", function(file) {
        // Show the total progress bar when upload starts
        document.querySelector("#total-progress").style.opacity = "1"
        // And disable the start button
        file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
    })

    // Hide the total progress bar when nothing's uploading anymore
    myDropzone.on("queuecomplete", function(progress) {
        document.querySelector("#total-progress").style.opacity = "0"
    })

    // Setup the buttons for all transfers
    // The "add files" button doesn't need to be setup because the config
    // `clickable` has already been specified.
    document.querySelector("#actions .start").onclick = function() {
        myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
    }
    document.querySelector("#actions .cancel").onclick = function() {
        myDropzone.removeAllFiles(true)
    }
    // DropzoneJS Demo Code End
</script>

<script>
    //Single Unit Property Registration DOM
    $(document).ready(function() {

        $("#sectionOneNextBtn").click(function(e) {
            e.preventDefault();

            $("#sectionOne").hide();
            $("#sectionTwo").show();

            $("#stepOneIndicatorNo").html('<i class="fa fa-check"><i>');
            $("#stepOneIndicatorNo").css('background-color', '#FFC107');
            $("#stepOneIndicatorNo").css('color', '#00192D');
            $("#stepOneIndicatorText").html('Done');

        });

        $("#sectionTwoBackBtn").click(function(e) {
            e.preventDefault();

            $("#sectionOne").show();
            $("#sectionTwo").hide();

            $("#stepOneIndicatorNo").html('1');
            $("#stepOneIndicatorNo").css('background-color', '#00192D');
            $("#stepOneIndicatorNo").css('color', '#FFC107');
            $("#stepOneIndicatorText").html('Overview');
        });

        $("#sectionTwoNextBtn").click(function(e) {
            e.preventDefault();

            $("#sectionThree").show();
            $("#sectionTwo").hide();

            $("#stepTwoIndicatorNo").html('<i class="fa fa-check"></i>');
            $("#stepTwoIndicatorNo").css('background-color', '#FFC107');
            $("#stepTwoIndicatorNo").css('color', '#00192D');
            $("#stepTwoIndicatorText").html('Done');
        });

        $("#sectionThreeBackBtn").click(function(e) {
            e.preventDefault();

            $("#sectionThree").hide();
            $("#sectionTwo").show();

            $("#stepTwoIndicatorNo").html('2');
            $("#stepTwoIndicatorNo").css('background-color', '#00192D');
            $("#stepTwoIndicatorNo").css('color', '#FFC107');
            $("#stepTwoIndicatorText").html('Identification');


        });

        $("#sectionThreeNextBtn").click(function(e) {
            e.preventDefault();

            $("#sectionFour").show();
            $("#sectionThree").hide();

            $("#stepThreeIndicatorNo").html('<i class="fa fa-check"></i>');
            $("#stepThreeIndicatorNo").css('background-color', '#FFC107');
            $("#stepThreeIndicatorNo").css('color', '#00192D');
            $("#stepThreeIndicatorText").html('Done');
        });

        $("#sectionFourBackBtn").click(function(e) {
            e.preventDefault();

            $("#sectionFour").hide();
            $("#sectionThree").show();

            $("#stepThreeIndicatorNo").html('3');
            $("#stepThreeIndicatorNo").css('background-color', '#00192D');
            $("#stepThreeIndicatorNo").css('color', '#FFC107');
            $("#stepThreeIndicatorText").html('Details');
        });

        $("#sectionFourNextBtn").click(function(e) {
            e.preventDefault();

            $("#sectionFive").show();
            $("#sectionFour").hide();
        });

    });
</script>

<!-- Monthly Expense Reimbursement Script -->
<script type="text/javascript">
    $(document).ready(function() {

        //Add A new Row when the Add New Row Button is clicked.
        $("#btn-add-row").click(function() {
            var row = "<tr><td><select id='' name='pname[]' class='form-control'><option value='' selected hidden>-- Select Option --</option><option value='Electricity'>Electricity</option><option value='Water'>Water</option><option value='Internet'>Internet</option><option value='Maintainance'>Maintainance</option><option value='Transport'>Transport</option><option value='Shopping'>Shopping</option><option value='Accomodation'>Accomodation</option><option value='Taxes'>Taxes</option><option value='Entertainment'>Entertainment</option><option value='Other'>Other</option></select></td><td><input type='text' class='form-control' id='description' name='expdesc[]' placeholder='Description' required></td><td><input type='text' required name='price[]' class='form-control price'></td><td><input type='text' required name='qty[]' class='form-control qty'></td><td><input type='text' required name='total[]' class='form-control total' readonly></td><td><button class='btn btn-xs btn-row-remove' style='background-color:#cc0001;color:#fff;'><i class='fa fa-trash'></i></button></td></tr>";
            $("#product_body").append(row);
        });

        //Remove the row if the remove row button is clicked
        $("body").on("click", ".btn-row-remove", function() {
            if (confirm("This item will be removed from the expense list. If you would wish to continue click OK")) {
                $(this).closest("tr").remove();
                grand_total();
            }
        });

        //Auto-Calculations
        $("body").on("keyup", ".price", function() {
            var price = Number($(this).val());
            var qty = Number($(this).closest("tr").find(".qty").val());
            $(this).closest("tr").find(".total").val(price * qty);
            grand_total();
        });

        $("body").on("keyup", ".qty", function() {
            var qty = Number($(this).val());
            var price = Number($(this).closest("tr").find(".price").val());
            $(this).closest("tr").find(".total").val(price * qty);
            grand_total();
        });

        function grand_total() {
            var tot = 0;
            $(".total").each(function() {
                tot += Number($(this).val());
            });
            $("#grand_total").val(tot);
        }

    });
</script>

<!-- Operational Costs Expense Reimbursement Form -->
<script type="text/javascript">
    $(document).ready(function() {

        //Add A new Row when the Add New Row Button is clicked.
        $("#btn-add-exp-row").click(function() {
            var operationalCostRow = "<tr><td><select id='exp_name' name='exp_name[]' class='form-control' required><option value='' selected hidden>-- Select Option --</option><option value='Office Supplies</option><option value='Electricity'>Electricity</option><option value='Fuel'>Fuel</option><option value='Electricity Token'>Electricity Token</option><option value='Travel Expenses'>Travel expenses</option><option value='Wages'>Wages</option><option value='Production Costs'>Production Costs</option><option value='Administrative Costs'>Administrative Costs</option></select></td><td><textarea name='desc[]' id='desc' class='form-control' placeholder='Description'></textarea></td><td><input type='text' required name='price[]' class='form-control price'></td><td><input type='text' required name='qty[]' class='form-control qty'></td><td><input type='text' required name='total[]' class='form-control total' readonly></td><td><button class='btn btn-sm btn-row-remove-exp' style='background-color:#cc0001;color:#fff;'><i class='fa fa-trash'></i></button></td></tr>";
            $("#exp_body").append(operationalCostRow);
        });

        //Remove the row if the remove row button is clicked
        $("body").on("click", ".btn-row-remove-exp", function() {
            if (confirm("This item will be removed from the expense list. If you would wish to continue click OK")) {
                $(this).closest("tr").remove();
                grand_total();
            }
        });

        //Auto-Calculations
        $("body").on("keyup", ".price", function() {
            var price = Number($(this).val());
            var qty = Number($(this).closest("tr").find(".qty").val());
            $(this).closest("tr").find(".total").val(price * qty);
            grand_total();
        });

        $("body").on("keyup", ".qty", function() {
            var qty = Number($(this).val());
            var price = Number($(this).closest("tr").find(".price").val());
            $(this).closest("tr").find(".total").val(price * qty);
            grand_total();
        });

        function grand_total() {
            var tot = 0;
            $(".total").each(function() {
                tot += Number($(this).val());
            });
            $("#grand_total").val(tot);
        }

    });
</script>




<script>
    //Show or Hide Plumbing Photos Section
    var plumbingPhotosDivDisplay = document.getElementById('plumbingPhotosDiv');

    function displayToInsertPlumbingPhotos() {
        plumbingPhotosDiv.style.display = 'block';
    }

    function displayToHidePlumbingPhotos() {
        plumbingPhotosDiv.style.display = 'none';
    }

    //Show or Hide Electrical Photos Section
    var electricalsPhotosDivSection = document.getElementById('electricalsPhotosDiv');

    function displayToInsertElectricalgPhotos() {
        electricalsPhotosDivSection.style.display = 'block';
    }

    function displayToHideElectricalPhotos(){
        electricalsPhotosDivSection.style.display = 'none';
    }

    //Show or Hide Structural Photos Section 
    var structuralPhotosDivDisplay = document.getElementById('structuralPhotosDiv');

    function displayToInsertStructuralPhotos() {
        structuralPhotosDivDisplay.style.display = 'block';
    }

    function displayToHideStructuralPhotos() {
        structuralPhotosDivDisplay.style.display = 'none';
    }
</script>

<!-- Mainteinance and Repairs DOM -->
<script type="text/javascript">
    //Requests DOM Script

    //plumbing detaileed DIV
    var plumbingRequestDetailsDetailsDisplay = document.getElementById('plumbingRequestDetails');

    //Electrical Detailed DIV
    var electricalRequestDetailsDisplay = document.getElementById('electricalRequestDetails');

    //Structural Detailed DIV
    var structuralRequestDetailsDisplay = document.getElementById('structuralRequestDetails');

    //Pest Control DIV
    var pestRequestDetailsDisplay = document.getElementById('pestRequestDetails');

    //HVAC Detailed DIV
    var hvacRequestDetailsDisplay = document.getElementById('hvacRequestDetails');

    //Painting and Finishing DIV
    var painitngFinishingRequestDetailsDisplay = document.getElementById('painitngFinishingRequestDetails');

    //Appliance Repairs Main DIV
    var applianceRepairsRequestDetailsDisplay = document.getElementById('applianceRepairsRequestDetails');

    //General Repairs Main DIV
    var generalRepairsRequestDetailsDisplay = document.getElementById('generalRepairsRequestDetails');

    //Outdoor Requests Main DIV
    var outDoorRepairsRequestDetailsDisplay = document.getElementById('outDoorRepairsRequestDetails');

    //Safety Security Main DIV
    var safetySecurityRequestDetailsDisplay = document.getElementById('safetySecurityRequestDetails');

    function displayPlumbingForm() {
        plumbingRequestDetailsDetailsDisplay.style.display =
            'block'; //display Electrical Main Form where the requiest is specified

        //Hide other forms
        electricalRequestDetailsDisplay.style.display = 'none';
        structuralRequestDetailsDisplay.style.display = 'none';
        pestRequestDetailsDisplay.style.display = 'none';
        hvacRequestDetailsDisplay.style.display = 'none';
        painitngFinishingRequestDetailsDisplay.style.display = 'none';
        applianceRepairsRequestDetailsDisplay.style.display = 'none';
        generalRepairsRequestDetailsDisplay.style.display = 'none';
        outDoorRepairsRequestDetailsDisplay.style.display = 'none';
        safetySecurityRequestDetailsDisplay.style.display = 'none';
    }

    function displayElectricalWorksForm() {
        electricalRequestDetailsDisplay.style.display =
            'block'; // Show the form where Electrical Request Form will be Specified

        //Hide other forms
        plumbingRequestDetailsDetailsDisplay.style.display = 'none';
        structuralRequestDetailsDisplay.style.display = 'none';
        pestRequestDetailsDisplay.style.display = 'none';
        hvacRequestDetailsDisplay.style.display = 'none';
        painitngFinishingRequestDetailsDisplay.style.display = 'none';
        applianceRepairsRequestDetailsDisplay.style.display = 'none';
        generalRepairsRequestDetailsDisplay.style.display = 'none';
        outDoorRepairsRequestDetailsDisplay.style.display = 'none';
        safetySecurityRequestDetailsDisplay.style.display = 'none';
    }

    function displaySTructuralRepairsForm() {
        structuralRequestDetailsDisplay.style.display =
            'block'; // Show Structural Form where request details will be specified

        //Hide Other Forms
        plumbingRequestDetailsDetailsDisplay.style.display = 'none';
        electricalRequestDetailsDisplay.style.display = 'none';
        pestRequestDetailsDisplay.style.display = 'none';
        hvacRequestDetailsDisplay.style.display = 'none';
        painitngFinishingRequestDetailsDisplay.style.display = 'none';
        applianceRepairsRequestDetailsDisplay.style.display = 'none';
        generalRepairsRequestDetailsDisplay.style.display = 'none';
        outDoorRepairsRequestDetailsDisplay.style.display = 'none';
        safetySecurityRequestDetailsDisplay.style.display = 'none';
    }

    function displayPestControlForm() {
        pestRequestDetailsDisplay.style.display =
            'block'; // Show Structural Form where request details will be specified

        //Hide Other Forms
        plumbingRequestDetailsDetailsDisplay.style.display = 'none';
        electricalRequestDetailsDisplay.style.display = 'none';
        structuralRequestDetailsDisplay.style.display = 'none';
        hvacRequestDetailsDisplay.style.display = 'none';
        painitngFinishingRequestDetailsDisplay.style.display = 'none';
        applianceRepairsRequestDetailsDisplay.style.display = 'none';
        generalRepairsRequestDetailsDisplay.style.display = 'none';
        outDoorRepairsRequestDetailsDisplay.style.display = 'none';
        safetySecurityRequestDetailsDisplay.style.display = 'none';
    }

    function displayHvacMaintenanceForm() {
        hvacRequestDetailsDisplay.style.display =
            'block'; // Show HVAC Form where request details will be specified

        //Hide Other Forms
        plumbingRequestDetailsDetailsDisplay.style.display = 'none';
        electricalRequestDetailsDisplay.style.display = 'none';
        structuralRequestDetailsDisplay.style.display = 'none';
        pestRequestDetailsDisplay.style.display = 'none';
        painitngFinishingRequestDetailsDisplay.style.display = 'none';
        applianceRepairsRequestDetailsDisplay.style.display = 'none';
        generalRepairsRequestDetailsDisplay.style.display = 'none';
        outDoorRepairsRequestDetailsDisplay.style.display = 'none';
        safetySecurityRequestDetailsDisplay.style.display = 'none';
    }

    function displayPainitingReqForm() {
        painitngFinishingRequestDetailsDisplay.style.display =
            'block'; // Show Painting and Finishing Form where request details will be specified

        //Hide Other Forms
        plumbingRequestDetailsDetailsDisplay.style.display = 'none';
        electricalRequestDetailsDisplay.style.display = 'none';
        structuralRequestDetailsDisplay.style.display = 'none';
        pestRequestDetailsDisplay.style.display = 'none';
        hvacRequestDetailsDisplay.style.display = 'none';
        applianceRepairsRequestDetailsDisplay.style.display = 'none';
        generalRepairsRequestDetailsDisplay.style.display = 'none';
        outDoorRepairsRequestDetailsDisplay.style.display = 'none';
        safetySecurityRequestDetailsDisplay.style.display = 'none';
    }

    function displayApplianceRepairForm() {
        applianceRepairsRequestDetailsDisplay.style.display =
            'block'; // Show Appliance Repairs Form where request details will be specified

        //Hide Other Forms
        plumbingRequestDetailsDetailsDisplay.style.display = 'none';
        electricalRequestDetailsDisplay.style.display = 'none';
        structuralRequestDetailsDisplay.style.display = 'none';
        pestRequestDetailsDisplay.style.display = 'none';
        hvacRequestDetailsDisplay.style.display = 'none';
        painitngFinishingRequestDetailsDisplay.style.display = 'none';
        generalRepairsRequestDetailsDisplay.style.display = 'none';
        outDoorRepairsRequestDetailsDisplay.style.display = 'none';
        safetySecurityRequestDetailsDisplay.style.display = 'none';
    }

    function displayGeneralRepairForm() {
        generalRepairsRequestDetailsDisplay.style.display =
            'block'; // Show General Repairs Form where request details will be specified

        //Hide Other Forms
        plumbingRequestDetailsDetailsDisplay.style.display = 'none';
        electricalRequestDetailsDisplay.style.display = 'none';
        structuralRequestDetailsDisplay.style.display = 'none';
        pestRequestDetailsDisplay.style.display = 'none';
        hvacRequestDetailsDisplay.style.display = 'none';
        painitngFinishingRequestDetailsDisplay.style.display = 'none';
        applianceRepairsRequestDetailsDisplay.style.display = 'none';
        outDoorRepairsRequestDetailsDisplay.style.display = 'none';
        safetySecurityRequestDetailsDisplay.style.display = 'none';
    }

    function displayOutdoorRepairForm() {
        outDoorRepairsRequestDetailsDisplay.style.display =
            'block'; // Show Outdoor Repairs Form where request details will be specified

        //Hide Other Forms
        plumbingRequestDetailsDetailsDisplay.style.display = 'none';
        electricalRequestDetailsDisplay.style.display = 'none';
        structuralRequestDetailsDisplay.style.display = 'none';
        pestRequestDetailsDisplay.style.display = 'none';
        hvacRequestDetailsDisplay.style.display = 'none';
        painitngFinishingRequestDetailsDisplay.style.display = 'none';
        applianceRepairsRequestDetailsDisplay.style.display = 'none';
        generalRepairsRequestDetailsDisplay.style.display = 'none';
        safetySecurityRequestDetailsDisplay.style.display = 'none';
    }

    function displaySafetySecurityForm() {
        safetySecurityRequestDetailsDisplay.style.display =
            'block'; // Show Security Request Form where request details will be specified

        //Hide Other Forms
        plumbingRequestDetailsDetailsDisplay.style.display = 'none';
        electricalRequestDetailsDisplay.style.display = 'none';
        structuralRequestDetailsDisplay.style.display = 'none';
        pestRequestDetailsDisplay.style.display = 'none';
        hvacRequestDetailsDisplay.style.display = 'none';
        painitngFinishingRequestDetailsDisplay.style.display = 'none';
        applianceRepairsRequestDetailsDisplay.style.display = 'none';
        generalRepairsRequestDetailsDisplay.style.display = 'none';
        outDoorRepairsRequestDetailsDisplay.style.display = 'none';
    }

    //Display an Option to Attach Plumbing Fault Photos if the user choses to include photos in the requeust form.
    var plumbingPhotosDivDisplay = document.getElementById('plumbingPhotosDiv');

    function displayToInsertPlumbingPhotos() {
        plumbingPhotosDiv.style.display = 'block';
    }

    function displayToHidePlumbingPhotos() {
        plumbingPhotosDiv.style.display = 'none';
    }

    //Validation of the Form Fields in the Plumbing Form Details (Plumbing Option)
    $(document).ready(function() {
        $("#confirmPlumbingRequest").click(function(e) {
            e.preventDefault();
            $("#plumbingIssueError").html('');
            $("#plumbingDescError").html('');
            $("#plumbingUrgencyError").html('');

            if ($("#plumbing_issue").val() == '') {
                $("#plumbingIssueError").html('Select to Fill Plumbing Issue');
                $("#plumbing_issue").css('border-color', '#cc0001');
                $("#plumbing_issue").css('background-color', '#FFDBDB');
                return false;

            } else if ($("#plumbing_desc").val() == '') {
                $("#plumbingDescError").html('Plumbing Description Required')
                $("#plumbing_desc").css('border-color', '#cc0001');
                $("#plumbing_desc").css('background-color', '#FFDBDB');
                return false;

            } else if ($("#plumbing_urgence").val() == '') {
                $("#plumbingUrgencyError").html('Urgence Level Required');
                $("#plumbing_urgence").css('border-color', '#cc0001');
                $("#plumbing_urgence").css('background-color', '#FFDBDB');
                return false;
            } else {
                window.location = 'process.php';
            }

        });
    });

    //Display an Option to Attach Photos Electric Fault Photos if the user choses to include in the request form.
    var electricPhotosDivDisplay = document.getElementById('electricPhotosDiv');

    function displayToInsertElectricPhotos() {
        electricPhotosDiv.style.display = 'block';
    }

    function displayToHideElectricPhotos() {
        electricPhotosDiv.style.display = 'none';
    }

    //Validate Electric Form Details
    $(document).ready(function() {
        $("#confirmElectricRequest").click(function(e) {
            e.preventDefault();
            $("#electricIssueError").html('');
            $("#electricDescError").html('');
            $("#electricUrgencyError").html('');

            if ($("#electric_issue").val() == '') {
                $("#electricIssueError").html('Select to Fill Electric Issue');
                $("#electric_issue").css('border-color', '#cc0001');
                $("#electric_issue").css('background-color', '#FFDBDB');
                return false;

            } else if ($("#electric_desc").val() == '') {
                $("#electricDescError").html('Electric Description Required')
                $("#electric_desc").css('border-color', '#cc0001');
                $("#electric_desc").css('background-color', '#FFDBDB');
                return false;

            } else if ($("#electric_urgence").val() == '') {
                $("#electricUrgencyError").html('Urgence Level Required');
                $("#electric_urgence").css('border-color', '#cc0001');
                $("#electric_urgence").css('background-color', '#FFDBDB');
                return false;
            } else {
                window.location = 'process.php';
            }

        });
    });

    //Display an Option to Attach Photos Structural Fault Photos if the user choses to include in the request form.
    var structuralPhotosDivDisplay = document.getElementById('structuralPhotosDiv');

    function displayToInsertStructuralPhotos() {
        structuralPhotosDiv.style.display = 'block';
    }

    function displayToHideStructuralPhotos() {
        structuralPhotosDiv.style.display = 'none';
    }

    //Validate Structural Form Details
    $(document).ready(function() {
        $("#confirmStructuralRequest").click(function(e) {
            e.preventDefault();
            $("#structuralIssueError").html('');
            $("#structuralDescError").html('');
            $("#structuralUrgencyError").html('');

            if ($("#structural_issue").val() == '') {
                $("#structuralIssueError").html('Select to Fill Structural Issue');
                $("#structural_issue").css('border-color', '#cc0001');
                $("#structural_issue").css('background-color', '#FFDBDB');
                return false;

            } else if ($("#structural_desc").val() == '') {
                $("#structuralDescError").html('Structural Description Required')
                $("#structural_desc").css('border-color', '#cc0001');
                $("#structural_desc").css('background-color', '#FFDBDB');
                return false;

            } else if ($("#structural_urgence").val() == '') {
                $("#structuralUrgencyError").html('Urgence Level Required');
                $("#structural_urgence").css('border-color', '#cc0001');
                $("#structural_urgence").css('background-color', '#FFDBDB');
                return false;
            } else {
                window.location = 'process.php';
            }

        });
    });

    //Display an Option to Attach Photos Pest Control Photos if the user choses to include in the request form.
    var pestPhotosDivDisplay = document.getElementById('pestPhotosDiv');

    function displayToInsertPestPhotos() {
        pestPhotosDiv.style.display = 'block';
    }

    function displayToHidePestPhotos() {
        pestPhotosDiv.style.display = 'none';
    }
    
    //Display an option to attach Photos for HVAC request if the user chooses to include them in the request form

    //Validate Pest Form Details
    $(document).ready(function() {
        $("#confirmPestRequest").click(function(e) {
            e.preventDefault();
            $("#pestIssueError").html('');
            $("#pestDescError").html('');
            $("#pestUrgencyError").html('');

            if ($("#pest_issue").val() == '') {
                $("#pestIssueError").html('Select to Fill Pest Issue');
                $("#pest_issue").css('border-color', '#cc0001');
                $("#pest_issue").css('background-color', '#FFDBDB');
                return false;

            } else if ($("#pest_desc").val() == '') {
                $("#pestDescError").html('Pest Description Required')
                $("#pest_desc").css('border-color', '#cc0001');
                $("#pest_desc").css('background-color', '#FFDBDB');
                return false;

            } else if ($("#pest_urgence").val() == '') {
                $("#pestUrgencyError").html('Urgence Level Required');
                $("#pest_urgence").css('border-color', '#cc0001');
                $("#pest_urgence").css('background-color', '#FFDBDB');
                return false;
            } else {
                window.location = 'process.php';
            }

        });
    });

    //Display an Option to Attach Photos for HVAC if the user choses to include in the request form.
    var hvacPhotosDivDisplay = document.getElementById('hvacPhotosDiv');

    function displayToInsertHvacPhotos() {
        hvacPhotosDiv.style.display = 'block';
    }

    function displayToHideHvacPhotos() {
        hvacPhotosDiv.style.display = 'none';
    }

    //Validate Pest Form Details
    $(document).ready(function() {
        $("#confirmHvacRequest").click(function(e) {
            e.preventDefault();
            $("#hvacIssueError").html('');
            $("#hvacDescError").html('');
            $("#hvacUrgencyError").html('');

            if ($("#hvac_issue").val() == '') {
                $("#hvacIssueError").html('Select to Fill HVAC Issue');
                $("#hvac_issue").css('border-color', '#cc0001');
                $("#hvac_issue").css('background-color', '#FFDBDB');
                return false;

            } else if ($("#hvac_desc").val() == '') {
                $("#hvacDescError").html('HVAC Description Required')
                $("#hvac_desc").css('border-color', '#cc0001');
                $("#hvac_desc").css('background-color', '#FFDBDB');
                return false;

            } else if ($("#hvac_urgence").val() == '') {
                $("#hvacUrgencyError").html('Urgence Level Required');
                $("#hvac_urgence").css('border-color', '#cc0001');
                $("#hvac_urgence").css('background-color', '#FFDBDB');
                return false;
            } else {
                window.location = 'process.php';
            }

        });
    });

    //Display an Option to Attach Photos for Paint and Finishing if the user choses to include in the request form.
    var paintfinishPhotosDivDisplay = document.getElementById('paintfinishPhotosDiv');

    function displayToInsertPaintFinishPhotos() {
        paintfinishPhotosDivDisplay.style.display = 'block';
    }

    function displayToHidePaintFinishPhotos() {
        paintfinishPhotosDivDisplay.style.display = 'none';
    }

    //Validate Pest Form Details
    $(document).ready(function() {
        $("#confirmPaintFinishRequest").click(function(e) {
            e.preventDefault();
            $("#paintfinishIssueError").html('');
            $("#paintfinishDescError").html('');
            $("#paintfinishUrgencyError").html('');

            if ($("#paintfinish_issue").val() == '') {
                $("#paintfinishIssueError").html('Select to Fill Paint and Finish Issue');
                $("#paintfinish_issue").css('border-color', '#cc0001');
                $("#paintfinish_issue").css('background-color', '#FFDBDB');
                return false;

            } else if ($("#paintfinish_desc").val() == '') {
                $("#paintfinishDescError").html('Paint and Fininsh Description Required')
                $("#paintfinish_desc").css('border-color', '#cc0001');
                $("#paintfinish_desc").css('background-color', '#FFDBDB');
                return false;

            } else if ($("#paintfinish_urgence").val() == '') {
                $("#paintfinishUrgencyError").html('Urgence Level Required');
                $("#paintfinish_urgence").css('border-color', '#cc0001');
                $("#paintfinish_urgence").css('background-color', '#FFDBDB');
                return false;
            } else {
                window.location = 'process.php';
            }

        });
    });

    //Display an Option to Attach Photos for Appliances Repair if the user choses to include in the request form.
    var appliancePhotosDivDisplay = document.getElementById('appliancePhotosDiv');

    function displayToInsertAppliancePhotos() {
        appliancePhotosDiv.style.display = 'block';
    }

    function displayToHideAppliancePhotos() {
        appliancePhotosDiv.style.display = 'none';
    }

    //Validate Pest Form Details
    $(document).ready(function() {
        $("#confirmAppliancesRequest").click(function(e) {
            e.preventDefault();
            $("#applianceIssueError").html('');
            $("#appliancefinishDescError").html('');
            $("#appliancefinishUrgencyError").html('');

            if ($("#appliance_issue").val() == '') {
                $("#applianceIssueError").html('Select to Appliance Mainteinance Issue');
                $("#appliance_issue").css('border-color', '#cc0001');
                $("#appliance_issue").css('background-color', '#FFDBDB');
                return false;

            } else if ($("#appliance_desc").val() == '') {
                $("#applianceDescError").html('Appliance Mainteinance Description Required');
                $("#appliance_desc").css('border-color', '#cc0001');
                $("#appliance_desc").css('background-color', '#FFDBDB');
                return false;

            } else if ($("#appliance_urgence").val() == '') {
                $("#applianceUrgencyError").html('Urgence Level Required');
                $("#appliance_urgence").css('border-color', '#cc0001');
                $("#appliance_urgence").css('background-color', '#FFDBDB');
                return false;
            } else {
                window.location = 'process.php';
            }

        });
    });



    //Display an Option to Attach Photos for General Repairs if the user choses to include in the request form.
    var generalPhotosDivDisplay = document.getElementById('generalPhotosDiv');

    function displayToInsertGeneralPhotos() {
        generalPhotosDiv.style.display = 'block';
    }

    function displayToHideGeneralPhotos() {
        generalPhotosDiv.style.display = 'none';
    }

    //Validate General Repairs Form Details
    $(document).ready(function() {
        $("#confirmGeneralRequest").click(function(e) {
            e.preventDefault();
            $("#generalIssueError").html('');
            $("#generalUrgencyError").html('');

            if ($("#general_issue").val() == '') {
                $("#generalIssueError").html('Select to General Mainteinance Issue');
                $("#general_issue").css('border-color', '#cc0001');
                $("#general_issue").css('background-color', '#FFDBDB');
                return false;

            } else if ($("#general_desc").val() == '') {
                $("#generalDescError").html('General Mainteinance Description Required');
                $("#general_desc").css('border-color', '#cc0001');
                $("#general_desc").css('background-color', '#FFDBDB');
                return false;

            } else if ($("#general_urgence").val() == '') {
                $("#generalUrgencyError").html('Urgence Level Required');
                $("#general_urgence").css('border-color', '#cc0001');
                $("#general_urgence").css('background-color', '#FFDBDB');
                return false;
            } else {
                window.location = 'process.php';
            }

        });
    });

    //Display an Option to Attach Photos for Outdoor Repairs if the user choses to include in the request form.
    var outdoorPhotosDivDisplay = document.getElementById('outdoorPhotosDiv');

    function displayToInsertOutdoorPhotos() {
        outdoorPhotosDivDisplay.style.display = 'block';
    }

    function displayToHideOutdoorPhotos() {
        outdoorPhotosDivDisplay.style.display = 'none';
    }

    //Validate Outdoor Request Form Details
    $(document).ready(function() {
        $("#confirmOutdoorRequest").click(function(e) {
            e.preventDefault();
            $("#outdoorIssueError").html('');
            $("#outdoorUrgencyError").html('');

            if ($("#outdoor_issue").val() == '') {
                $("#outdoorIssueError").html('Select to Outdoor Mainteinance Issue');
                $("#outdoor_issue").css('border-color', '#cc0001');
                $("#outdoor_issue").css('background-color', '#FFDBDB');
                return false;

            } else if ($("#outdoor_desc").val() == '') {
                $("#outdoorDescError").html('Outdoor Mainteinance Description Required');
                $("#outdoor_desc").css('border-color', '#cc0001');
                $("#outdoor_desc").css('background-color', '#FFDBDB');
                return false;

            } else if ($("#outdoor_urgence").val() == '') {
                $("#outdoorUrgencyError").html('Urgence Level Required');
                $("#outdoor_urgence").css('border-color', '#cc0001');
                $("#outdoor_urgence").css('background-color', '#FFDBDB');
                return false;
            } else {
                window.location = 'process.php';
            }

        });
    });

    //Display an Option to Attach Photos for Safety and Security Repairs or requests if the user choses to include in the request form.
    var safetyPhotosDivDisplay = document.getElementById('safetyPhotosDiv');

    function displayToInsertSafetyPhotos() {
        safetyPhotosDivDisplay.style.display = 'block';
    }

    function displayToHideSafetyPhotos() {
        safetyPhotosDivDisplay.style.display = 'none';
    }

    //Validate Outdoor Request Form Details
    $(document).ready(function() {
        $("#confirmSafetyRequest").click(function(e) {
            e.preventDefault();
            $("#safetyIssueError").html('');
            $("#safetyUrgencyError").html('');

            if ($("#safety_issue").val() == '') {
                $("#safetyIssueError").html('Select to Fill Safety and Security Request Details Issue');
                $("#safety_issue").css('border-color', '#cc0001');
                $("#safety_issue").css('background-color', '#FFDBDB');
                return false;

            } else if ($("#safety_desc").val() == '') {
                $("#safetyDescError").html('Safety and Security Request Description Required');
                $("#safety_desc").css('border-color', '#cc0001');
                $("#safety_desc").css('background-color', '#FFDBDB');
                return false;

            } else if ($("#safety_urgence").val() == '') {
                $("#safetyUrgencyError").html('Urgence Level Required');
                $("#safety_urgence").css('border-color', '#cc0001');
                $("#safety_urgence").css('background-color', '#FFDBDB');
                return false;
            } else {
                window.location = 'process.php';
            }

        });
    });

    //Display Payment Option upon selection of an Option
    document.getElementById('optionsPayment').addEventListener('change', function() {
        var selectedValue = this.value;
        var divs = document.getElementsByClassName('paymentOptions');

        // Hide all divs
        for (var i = 0; i < divs.length; i++) {
            divs[i].style.display = 'none';
        }

        // Display the selected div
        if (selectedValue) {
            document.getElementById(selectedValue).style.display = 'block';
        }
    });
</script>
<script>
    //this is a JavaScript Code that will allow you to specify Main Tenant Information Upon selection of Main Tenant Option

    //This will be the stepper registration form JavaScript
    var specifyOtherTenantsSection = document.getElementById('specifyOtherTenants'); //This is a variable that holds the section where one can specify the occupats

    //Event listener to initialize the selection of Yes as an option
    document.getElementById('yesOtherOccupants').addEventListener('change', function(){
        specifyOtherTenantsSection.style.display="block"; //Display a section where occupants specifications will be entered when a user selects Yes
    });

    //Event Listener to iniialize the selection of No as an Option
    document.getElementById('noOtherOccupants').addEventListener('change', function(){
        specifyOtherTenantsSection.style.display="none"; //Hide a section where Occupants sepecification will be hidden when a user selects No
    });

    //Event Listener to Specify if the Tenant Owns Pets
    document.getElementById('customSwitchPetYes').addEventListener('change', function(){
        document.getElementById('specifyPetsCard').style.display='block';
    });

    document.getElementById('customNoPets').addEventListener('change', function(){
        document.getElementById('specifyPetsCard').style.display='none';
    });

    //Event Listener to Specify Employment Information
    document.getElementById('employmentSelectionOption').addEventListener('change', function() {
        document.getElementById('employmentCard').style.display='block';
    });

    document.getElementById('business').addEventListener('change', function() {
        document.getElementById('employmentCard').style.display='none';
    });

    document.getElementById('empBus').addEventListener('change', function() {
        document.getElementById('employmentCard').style.display='none';
    });

    $(document).ready(function(){
        var tenant_f_nameError = '';
        var tenant_m_nameError = '';
        var tenant_l_nameError = '';
        var tenant_m_contactError = '';
        var tenant_a_contactError = '';
        var tenant_emailError = '';
        var tenant_id_noError = '';
        var tenant_id_copyError = '';

        var tenant_f_name = '';
        var tenant_m_name = '';
        var tenant_l_name = '';
        var tenant_m_contact = '';
        var tenant_a_contact = '';
        var tenant_email = '';
        var tenant_id_no = '';
        var tenant_id_copy = '';

        $("#firstStepNextBtn").click(function(e) {
            e.preventDefault();
            alert('Hi');
            if($("#tenant_f_name").val() == '') {
                $("#tenant_f_nameError").html('First Name Required');
                $("#tenant_f_name").css('background-color','#FFDBDB');
                return false;

            } else if ($("#tenant_m_name").val() == '') {
                $("#tenant_m_nameError").html('Middle Name Required');
                $("#tenant_m_name").css('background-color','#FFDBDB');
                return false;
            } else if ($("#tenant_l_name").val() == '') {
                $("#tenant_l_nameError").html('Last Name Required');
                $("#tenant_l_name").css('background-color','#FFDBDB');
                return false;

            } else if ($("#tenant_m_name").val() == $("#tenant_f_name").val()) {
                $("#tenant_m_nameError").html('Middle & First Name can\'t the Same');
                $("#tenant_m_name").css('background-color','#FFDBDB');
                return false;

            } else if ($("#tenant_m_contact").val() == '') {
                $("#tenant_m_contactError").html('Contact Information Required');
                $("#tenant_m_contact").css('background-color','#FFDBDB');
                return false;

            } else if ($("#tenant_a_contact") .val() == $("#tenant_m_contact").val()) {
                $("#tenant_a_contactError").html('Contacts can\'t be the Same');
                $("#tenant_a_contact").css('background-color','#FFDBDB');
                return false;
            } else if ($("#tenant_email").val() == '') {
                $("#tenant_emailError").html('Email Required');
                $("#tenant_email").css('background-color','#FFDBDB');
                return false;

            } else if ($("#tenant_id_no").val() == '') {
                $("#tenant_id_noError").html('Identification No. Required');
                $("#tenant_id_no").css('background-color','#FFDBDB');
                return false;
            } else if ($("#tenant_id_no").val() == $("#tenant_a_contact").val()) {
                $("#tenant_id_noError").html('Identification & Contact No. can\'t be the Same');
                $("#tenant_id_no").css('background-color','#FFDBDB');
                return false;

            } else if ($("#tenant_id_copy").val() == '') {
                $("#tenant_id_copyError").html('Identification Copy Required');
                $("#tenant_id_copy").css('background-color','#FFDBDB');
                return false;

            } else {
                $("#sectionTwoOccpantsInfo").show();
                $("#sectionOnePersonalInfo").hide();
                $("#stepOneIndicatorNo").html('<i class="fa fa-check"></i>');
                $("#stepOneIndicatorNo").css('background-color', '#FFC107');
                $("#stepOneIndicatorNo").css('color', '#00192D');
                $("#stepOneIndicatorText").html('Done');

                //Change the Field's Properties
                $("#tenant_f_nameError").html('');
                $("#tenant_f_name").css('border','1px solid #379E1B');
                $("#tenant_f_name").css('background-color','rgb(55, 158, 27, .3)');
            }
        });
        $('#secondStepPreviousBtn').click(function(e){
            e.preventDefault();
            $("#sectionTwoOccpantsInfo").hide();
            $("#sectionOnePersonalInfo").show();
            $("#stepOneIndicatorNo").html('1');
            $("#stepOneIndicatorNo").css('background-color', '#00192D');
            $("#stepOneIndicatorNo").css('color', '#FFC107');
            $("#stepOneIndicatorText").html('Personal Information');
        });
        $("#secondStepNextBtn").click(function(e){
            e.preventDefault();
            alert ('Validation Pending. I\'ll get back to this');

            $("#sectionThreePetsInfo").show();
            $("#sectionTwoOccpantsInfo").hide();

            $("#stepTwoIndicatorNo").html('<i class="fa fa-check"></i>');
            $("#stepTwoIndicatorNo").css('background-color', '#FFC107');
            $("#stepTwoIndicatorNo").css('color', '#00192D');
            $("#stepTwoIndicatorText").html('Done');

        });
        $("#thirdStepPreviousBtn").click(function(e){
            e.preventDefault();

            $("#sectionTwoOccpantsInfo").show();
            $("#sectionThreePetsInfo").hide();

            $("#stepTwoIndicatorNo").html('2');
            $("#stepTwoIndicatorNo").css('background-color', '#00192D');
            $("#stepTwoIndicatorNo").css('color', '#FFC107');
            $("#stepTwoIndicatorText").html('Occupants Information');
        });
        $("#thirdStepNextBtn").click(function(e){
            e.preventDefault();

            $("#sectionFourIncomeSourceInfo").show();
            $("#sectionThreePetsInfo").hide();

            $("#stepThreeIndicatorNo").html('<i class="fa fa-check"></i>');
            $("#stepThreeIndicatorNo").css('background-color', '#FFC107');
            $("#stepThreeIndicatorNo").css('color', '#00192D');
            $("#stepThreeIndicatorText").html('Done');
        });
        $("#fourthStepPreviousBtn").click(function(e){
            e.preventDefault();

            $("#sectionFourIncomeSourceInfo").hide();
            $("#sectionThreePetsInfo").show();

            $("#stepThreeIndicatorNo").html('3');
            $("#stepThreeIndicatorNo").css('background-color', '#00192D');
            $("#stepThreeIndicatorNo").css('color', '#FFC107');
            $("#stepThreeIndicatorText").html('Pets Information');

        });
        $("#fourthStepNextBtn").click(function(e){
            e.preventDefault();

            $("#sectionFiveRentalAgreementInfo").show();
            $("#sectionFourIncomeSourceInfo").hide();

            $("#stepFourIndicatorNo").html('<i class="fa fa-check"></i>');
            $("#stepFourIndicatorNo").css('background-color', '#FFC107');
            $("#stepFourIndicatorNo").css('color', '#00192D');
            $("#stepFourIndicatorText").html('Done');
        });
        $("#fifththStepPreviousBtn").click(function(e){
            e.preventDefault();

            $("#sectionFiveRentalAgreementInfo").hide();
            $("#sectionFourIncomeSourceInfo").show();

            $("#stepFourIndicatorNo").html('4');
            $("#stepFourIndicatorNo").css('background-color', '#00192D');
            $("#stepFourIndicatorNo").css('color', '#FFC107');
            $("#stepFourIndicatorText").html('Source of Income');
        });
    });
</script>
<!-- Script Display File for the User when attachment is done -->
<script>
    // Function to handle multiple files selection
    function handleFiles(event) {
        const files = event.target.files;  // Get all selected files
        const previewContainer = document.getElementById('filePreviews');
        previewContainer.innerHTML = '';  // Clear previous previews

        let imageCount = 0; // Keep track of how many images we preview

        Array.from(files).forEach(file => {
            const fileSizeInMB = (file.size / (1024 * 1024)).toFixed(2);  // Convert to MB
            const fileType = file.type;

            // Create a container for each file's preview and size
            const fileContainer = document.createElement('div');
            fileContainer.style.marginBottom = '30px';

            // Display the file size
            const fileSizeElement = document.createElement('p');
            fileSizeElement.textContent = `File size: ${fileSizeInMB} MB`;
            fileContainer.appendChild(fileSizeElement);

            // Preview the file based on type
            if (fileType.startsWith('image/')) {
                if (imageCount >= 3) {
                    const warning = document.createElement('p');
                    warning.style.color = 'red';
                    warning.textContent = 'You can only upload 3 images at a time.';
                    previewContainer.appendChild(warning);
                    return;
                }

                const img = document.createElement('img');
                img.style.width = '70%';
                img.style.display = 'flex';
                img.src = URL.createObjectURL(file);
                img.onload = function () {
                    URL.revokeObjectURL(img.src); // Free memory
                };

                fileContainer.appendChild(img);
                imageCount++;

            } else if (fileType === 'application/pdf') {
                const pdfEmbed = document.createElement('embed');
                pdfEmbed.style.width = '100%';
                pdfEmbed.style.height = '100%';
                pdfEmbed.src = URL.createObjectURL(file);
                fileContainer.appendChild(pdfEmbed);

            } else {
                const fileName = document.createElement('p');
                fileName.textContent = `File: ${file.name}`;
                fileContainer.appendChild(fileName);
            }

            // Append the file container to the previews section
            previewContainer.appendChild(fileContainer);
        });
    }
</script>


<!-- Chatbot Javascript -->
<!-- Chat Js -->
<script type="text/javascript">
    // Get chatbot elements
    const chatbot = document.getElementById('chatbot');
    const conversation = document.getElementById('conversation');
    const inputForm = document.getElementById('input-form');
    const inputField = document.getElementById('input-field');


    // Add event listener to input form
    inputForm.addEventListener('submit', function(event) {
        // Prevent form submission
        event.preventDefault();

        // Get user input
        const input = inputField.value;

        // Clear input field
        inputField.value = '';
        const currentTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: "2-digit" });

        // Add user input to conversation
        let message = document.createElement('div');
        message.classList.add('chatbot-message', 'user-message');
        message.innerHTML = `<p class="chatbot-text" sentTime="${currentTime}">${input}</p>`;
        conversation.appendChild(message);

        // Generate chatbot response
        const response = generateResponse(input);

        // Add chatbot response to conversation
        message = document.createElement('div');
        message.classList.add('chatbot-message','chatbot');
        message.innerHTML = `<p class="chatbot-text" sentTime="${currentTime}">${response}</p>`;
        conversation.appendChild(message);
        message.scrollIntoView({behavior: "smooth"});
    });

    // Generate chatbot response function
    function generateResponse(input) {
        // Add chatbot logic here
        const responses = [
            "Hello, how can I help you today? 😊",
            "I'm sorry, I didn't understand your question. Could you please rephrase it? 😕",
            "I'm here to assist you with any questions or concerns you may have. 📩",
            "I'm sorry, I'm not able to browse the internet or access external information. Is there anything else I can help with? 💻",
            "What would you like to know? 🤔",
            "I'm sorry, I'm not programmed to handle offensive or inappropriate language. Please refrain from using such language in our conversation. 🚫",
            "I'm here to assist you with any questions or problems you may have. How can I help you today? 🚀",
            "Is there anything specific you'd like to talk about? 💬",
            "I'm happy to help with any questions or concerns you may have. Just let me know how I can assist you. 😊",
            "I'm here to assist you with any questions or problems you may have. What can I help you with today? 🤗",
            "Is there anything specific you'd like to ask or talk about? I'm here to help with any questions or concerns you may have. 💬",
            "I'm here to assist you with any questions or problems you may have. How can I help you today? 💡",
        ];

        // Return a random response
        return responses[Math.floor(Math.random() * responses.length)];
    }
    //tab switch

    /*window.onblur = function (tabs) { 
        alert('Trying to Leave this Page Huh 🤔!'); 
      };*/
</script>
        
<!-- Tenant Requests DOM Scripts for Button Behaviors-->
<script>
    $(document).ready(function(){
        $("#firstSectionNextBtn").click(function(e){
            e.preventDefault();
            $("#secondSection").show();
            $("#firstSection").hide();
        });
        $("#secondSectionBackBtn").click(function(e){
            e.preventDefault();
            $("#secondSection").hide();
            $("#firstSection").show();
        });
        $("#secondSectionNextBtn").click(function(e){
            e.preventDefault();
            $("#thirdSection").show();
            $("#secondSection").hide();
        });
        $("#thirdSectionBackBtn").click(function(e){
            e.preventDefault();
            $("#thirdSection").hide();
            $("#secondSection").show();
        });
        $("#thirdSectionNextBtn").click(function(e){
            e.preventDefault();
            $("#fourthSection").show();
            $("#thirdSection").hide();
        });
        $("#fourthSectionBackBtn").click(function(e){
            e.preventDefault();
            e.preventDefault();
            $("#fourthSection").hide();
            $("#thirdSection").show();
        });
    });
</script>

        