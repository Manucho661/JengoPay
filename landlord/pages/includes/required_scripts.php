<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="plugins/popper/popper.min.js"></script>
<!-- Core Scripts -->
<script src="dist/js/adminlte.js"></script>
<!-- Core Scripts dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard3.js"></script>
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Summernote -->
<script src="../plugins/summernote/summernote-bs4.min.js"></script>
<!-- Advanced Form Features Scripts -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="plugins/dropzone/min/dropzone.min.js"></script>
<!-- Core Scripts for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/npm_chart.js"></script>
<!-- Summernote -->
<script>
$(function() {
  // Summernote
  $('#summernote').summernote()

  // CodeMirror
  CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
    mode: "htmlmixed",
    theme: "monokai"
  });
})
</script>
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
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
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
const companyAsManagerDetailsSection = document.getElementById('companyAsManagerDetails');

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
  var _0x360384 = ['1988308MFbCZz', 'none', '3wcLRTl', 'block', '2240820AAihcU', '6IOVELk', '260xlsIjR', '89514jaxpsu',
    'display', '1199741mMklLN', 'helpForm', '2760142vOGmXu', '5974905XuzUyx', 'getElementById', '4639024sboHuE',
    'style'
  ];
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
      var _0x4d98b7 = -parseInt(_0x338209(0x13b)) / 0x1 + parseInt(_0x338209(0x136)) / 0x2 * (-parseInt(_0x338209(
        0x134)) / 0x3) + parseInt(_0x338209(0x132)) / 0x4 + -parseInt(_0x338209(0x13e)) / 0x5 * (-parseInt(
        _0x338209(0x137)) / 0x6) + parseInt(_0x338209(0x13d)) / 0x7 + parseInt(_0x338209(0x140)) / 0x8 + -parseInt(
        _0x338209(0x139)) / 0x9 * (-parseInt(_0x338209(0x138)) / 0xa);
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
  var _0x297681 = ['block', '250JoGcJc', '5948600VuKOnm', 'Last\x20Name\x20Required\x20Before\x20you\x20Close', 'none',
    '4414LEGmEU', '4686780xABWVD', '50860PStEag', '295FDnylZ', '4476042GJSquo', '#lastName',
    'Phone\x20Number\x20Required\x20before\x20you\x20Close', '3749625pGSygv',
    'Owner\x20Email\x20Required\x20before\x20you\x20Close', '38676ANLuap', 'style', '#individualCloseBtn',
    'Last\x20Name\x20Last\x20Name\x20can\x27t\x20be\x20the\x20same\x20as\x20First\x20Name', 'display', '#phoneNumber',
    'val', 'click'
  ];
  _0x36dd = function() {
    return _0x297681;
  };
  return _0x36dd();
}(function(_0x27219b, _0x3bf112) {
  var _0x1b014f = _0x37d4,
    _0x2aea38 = _0x27219b();
  while (!![]) {
    try {
      var _0x2ccf21 = -parseInt(_0x1b014f(0x6e)) / 0x1 * (parseInt(_0x1b014f(0x81)) / 0x2) + parseInt(_0x1b014f(
        0x74)) / 0x3 + -parseInt(_0x1b014f(0x6d)) / 0x4 * (-parseInt(_0x1b014f(0x7d)) / 0x5) + parseInt(_0x1b014f(
          0x6f)) / 0x6 + -parseInt(_0x1b014f(0x82)) / 0x7 + parseInt(_0x1b014f(0x7e)) / 0x8 + -parseInt(_0x1b014f(
          0x72)) / 0x9;
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


//Show Entity as the Building Owner
(function(_0x248a5e, _0x2727e2) {
  var _0x17728a = _0x5035,
    _0x2894f8 = _0x248a5e();
  while (!![]) {
    try {
      var _0x25f3ba = parseInt(_0x17728a(0xb9)) / 0x1 * (-parseInt(_0x17728a(0xc3)) / 0x2) + parseInt(_0x17728a(
        0xaf)) / 0x3 + parseInt(_0x17728a(0xc1)) / 0x4 + parseInt(_0x17728a(0xb4)) / 0x5 * (-parseInt(_0x17728a(
          0xbe)) / 0x6) + -parseInt(_0x17728a(0xb3)) / 0x7 + parseInt(_0x17728a(0xb1)) / 0x8 * (-parseInt(_0x17728a(
          0xc0)) / 0x9) + -parseInt(_0x17728a(0xc6)) / 0xa * (-parseInt(_0x17728a(0xc2)) / 0xb);
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
  var _0x1e8c55 = ['val', '#entityRepRole', 'Entity\x20Representative\x20Role\x20Required', '490008NxgpPG', 'style',
    '8eOyUJB', '#entityRepresentative', '199570ujpDry', '102535ZYWBin', 'display', '#entityEmail', '#entityName',
    'preventDefault', '32914mYDHti', 'Entity\x20Representative\x20Required', 'getElementById', 'click',
    'entityInfoDiv', '84ZwtMuv', 'Entity\x20Email\x20Required\x20before\x20you\x20Close', '300249eFTGAF',
    '17236EznKiF', '641575xHySxy', '4bYxigF', 'block', 'Entity\x20Name\x20Required\x20before\x20you\x20Close',
    '70FgpscT'
  ];
  _0x5cef = function() {
    return _0x1e8c55;
  };
  return _0x5cef();
}

//Close Individual Information Modal
function closeIndividualOwnerInfo() {
  var individualOwnerModal = document.getElementById('individual-owner');
  individualOwnerModal.style.display = 'none';
}

function closeEntityOwnership() {
  var entityOwnerModal = document.getElementById('entity-owner');
  entityOwnerModal.style.display = 'none';
}
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

<!-- Adding Units Recurring Expenses Reimbursement Script -->
<!-- Recurring Bills JavaScript -->
<script>
const billOptions = [
  "Water",
  "Electricity",
  "Garbage",
  "Internet",
  "Security",
  "Management Fee"
];

function addRow() {
  const tbody = document.querySelector("#expensesTable tbody");
  const row = document.createElement("tr");

  // build select dropdown with available bills
  let options = '<option value="">-- Select Bill --</option>';
  billOptions.forEach(opt => {
    options += `<option value="${opt}">${opt}</option>`;
  });

  row.innerHTML =
    `<td>
                            <select class="form-control" name="bill[]" onchange="refreshBillOptions()" required>
                                ${options}
                            </select>
                        </td>
                        <td><input type="number" class="form-control" name="qty[]" value="0" min="0" step="1" oninput="updateTotals(this)" required></td>
                        <td><input type="number" class="form-control" name="unit_price[]" value="0" min="0" step="0.01" oninput="updateTotals(this)" required></td>
                        <td class="subtotal">0.00</td>
        <td><button type="button" class="btn btn-sm shadow" style="background-color:#cc0001; color:#fff;" onclick="removeRow(this)"><i class="bi bi-trash"></i></button></td>`;

  tbody.appendChild(row);
  refreshBillOptions();
}

function removeRow(button) {
  button.closest("tr").remove();
  refreshBillOptions();
  recalcTotals();
}

function refreshBillOptions() {
  // Collect all selected bills
  const selectedBills = Array.from(document.querySelectorAll('select[name="bill[]"]'))
    .map(sel => sel.value)
    .filter(val => val !== "");

  // Update all selects
  document.querySelectorAll('select[name="bill[]"]').forEach(select => {
    const currentValue = select.value;
    select.innerHTML = '<option value="">-- Select Bill --</option>';

    billOptions.forEach(opt => {
      // Keep current value available, hide others if already selected elsewhere
      if (!selectedBills.includes(opt) || opt === currentValue) {
        const option = document.createElement("option");
        option.value = opt;
        option.textContent = opt;
        if (opt === currentValue) option.selected = true;
        select.appendChild(option);
      }
    });
  });
}

function updateTotals(input) {
  const row = input.closest("tr");
  const qty = parseFloat(row.querySelector('input[name="qty[]"]').value) || 0;
  const price = parseFloat(row.querySelector('input[name="unit_price[]"]').value) || 0;
  const subtotal = qty * price;

  row.querySelector(".subtotal").textContent = subtotal.toFixed(2);

  recalcTotals();
}

function recalcTotals() {
  let totalQty = 0;
  let totalUnitPrice = 0;
  let totalSubtotal = 0;

  document.querySelectorAll("#expensesTable tbody tr").forEach(row => {
    const qty = parseFloat(row.querySelector('input[name="qty[]"]').value) || 0;
    const price = parseFloat(row.querySelector('input[name="unit_price[]"]').value) || 0;
    const subtotal = parseFloat(row.querySelector(".subtotal").textContent) || 0;

    totalQty += qty;
    totalUnitPrice += price;
    totalSubtotal += subtotal;
  });

  document.getElementById("totalQty").textContent = totalQty;
  document.getElementById("totalUnitPrice").textContent = totalUnitPrice.toFixed(2);
  document.getElementById("totalSubtotal").textContent = totalSubtotal.toFixed(2);
}
</script>




<!-- Show or Hide Plumbing Photos Section -->
<script>
var plumbingPhotosDivDisplay = document.getElementById('plumbingPhotosDiv');

function displayToInsertPlumbingPhotos() {
  plumbingPhotosDiv.style.display = 'block';
}

function displayToHidePlumbingPhotos() {
  plumbingPhotosDiv.style.display = 'none';
}
</script>

<!-- Mainteinance and Repairs DOM -->
<script type="text/javascript">
//Requests DOM Script

//plumbing detaileed DIV
var plumbingRequestDetailsDetailsDisplay = document.getElementById('plumbingRequestDetails');

//Electrical Detialed DIV
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
var specifyOtherTenantsSection = document.getElementById(
'specifyOtherTenants'); //This is a variable that holds the section where one can specify the occupats

//Event listener to initialize the selection of Yes as an option
document.getElementById('yesOtherOccupants').addEventListener('change', function() {
  specifyOtherTenantsSection.style.display =
  "block"; //Display a section where occupants specifications will be entered when a user selects Yes
});

//Event Listener to iniialize the selection of No as an Option
document.getElementById('noOtherOccupants').addEventListener('change', function() {
  specifyOtherTenantsSection.style.display =
  "none"; //Hide a section where Occupants sepecification will be hidden when a user selects No
});

//Event Listener to Specify if the Tenant Owns Pets
document.getElementById('customSwitchPetYes').addEventListener('change', function() {
  document.getElementById('specifyPetsCard').style.display = 'block';
});

document.getElementById('customNoPets').addEventListener('change', function() {
  document.getElementById('specifyPetsCard').style.display = 'none';
});

//Event Listener to Specify Employment Information
document.getElementById('employmentSelectionOption').addEventListener('change', function() {
  document.getElementById('employmentCard').style.display = 'block';
});

document.getElementById('business').addEventListener('change', function() {
  document.getElementById('employmentCard').style.display = 'none';
});

document.getElementById('empBus').addEventListener('change', function() {
  document.getElementById('employmentCard').style.display = 'none';
});

$(document).ready(function() {
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
    if ($("#tenant_f_name").val() == '') {
      $("#tenant_f_nameError").html('First Name Required');
      $("#tenant_f_name").css('background-color', '#FFDBDB');
      return false;

    } else if ($("#tenant_m_name").val() == '') {
      $("#tenant_m_nameError").html('Middle Name Required');
      $("#tenant_m_name").css('background-color', '#FFDBDB');
      return false;
    } else if ($("#tenant_l_name").val() == '') {
      $("#tenant_l_nameError").html('Last Name Required');
      $("#tenant_l_name").css('background-color', '#FFDBDB');
      return false;

    } else if ($("#tenant_m_name").val() == $("#tenant_f_name").val()) {
      $("#tenant_m_nameError").html('Middle & First Name can\'t the Same');
      $("#tenant_m_name").css('background-color', '#FFDBDB');
      return false;

    } else if ($("#tenant_m_contact").val() == '') {
      $("#tenant_m_contactError").html('Contact Information Required');
      $("#tenant_m_contact").css('background-color', '#FFDBDB');
      return false;

    } else if ($("#tenant_a_contact").val() == $("#tenant_m_contact").val()) {
      $("#tenant_a_contactError").html('Contacts can\'t be the Same');
      $("#tenant_a_contact").css('background-color', '#FFDBDB');
      return false;
    } else if ($("#tenant_email").val() == '') {
      $("#tenant_emailError").html('Email Required');
      $("#tenant_email").css('background-color', '#FFDBDB');
      return false;

    } else if ($("#tenant_id_no").val() == '') {
      $("#tenant_id_noError").html('Identification No. Required');
      $("#tenant_id_no").css('background-color', '#FFDBDB');
      return false;
    } else if ($("#tenant_id_no").val() == $("#tenant_a_contact").val()) {
      $("#tenant_id_noError").html('Identification & Contact No. can\'t be the Same');
      $("#tenant_id_no").css('background-color', '#FFDBDB');
      return false;

    } else if ($("#tenant_id_copy").val() == '') {
      $("#tenant_id_copyError").html('Identification Copy Required');
      $("#tenant_id_copy").css('background-color', '#FFDBDB');
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
      $("#tenant_f_name").css('border', '1px solid #379E1B');
      $("#tenant_f_name").css('background-color', 'rgb(55, 158, 27, .3)');
    }
  });
  $('#secondStepPreviousBtn').click(function(e) {
    e.preventDefault();
    $("#sectionTwoOccpantsInfo").hide();
    $("#sectionOnePersonalInfo").show();
    $("#stepOneIndicatorNo").html('1');
    $("#stepOneIndicatorNo").css('background-color', '#00192D');
    $("#stepOneIndicatorNo").css('color', '#FFC107');
    $("#stepOneIndicatorText").html('Personal Information');
  });
  $("#secondStepNextBtn").click(function(e) {
    e.preventDefault();
    alert('Validation Pending. I\'ll get back to this');

    $("#sectionThreePetsInfo").show();
    $("#sectionTwoOccpantsInfo").hide();

    $("#stepTwoIndicatorNo").html('<i class="fa fa-check"></i>');
    $("#stepTwoIndicatorNo").css('background-color', '#FFC107');
    $("#stepTwoIndicatorNo").css('color', '#00192D');
    $("#stepTwoIndicatorText").html('Done');

  });
  $("#thirdStepPreviousBtn").click(function(e) {
    e.preventDefault();

    $("#sectionTwoOccpantsInfo").show();
    $("#sectionThreePetsInfo").hide();

    $("#stepTwoIndicatorNo").html('2');
    $("#stepTwoIndicatorNo").css('background-color', '#00192D');
    $("#stepTwoIndicatorNo").css('color', '#FFC107');
    $("#stepTwoIndicatorText").html('Occupants Information');
  });
  $("#thirdStepNextBtn").click(function(e) {
    e.preventDefault();

    $("#sectionFourIncomeSourceInfo").show();
    $("#sectionThreePetsInfo").hide();

    $("#stepThreeIndicatorNo").html('<i class="fa fa-check"></i>');
    $("#stepThreeIndicatorNo").css('background-color', '#FFC107');
    $("#stepThreeIndicatorNo").css('color', '#00192D');
    $("#stepThreeIndicatorText").html('Done');
  });
  $("#fourthStepPreviousBtn").click(function(e) {
    e.preventDefault();

    $("#sectionFourIncomeSourceInfo").hide();
    $("#sectionThreePetsInfo").show();

    $("#stepThreeIndicatorNo").html('3');
    $("#stepThreeIndicatorNo").css('background-color', '#00192D');
    $("#stepThreeIndicatorNo").css('color', '#FFC107');
    $("#stepThreeIndicatorText").html('Pets Information');

  });
  $("#fourthStepNextBtn").click(function(e) {
    e.preventDefault();

    $("#sectionFiveRentalAgreementInfo").show();
    $("#sectionFourIncomeSourceInfo").hide();

    $("#stepFourIndicatorNo").html('<i class="fa fa-check"></i>');
    $("#stepFourIndicatorNo").css('background-color', '#FFC107');
    $("#stepFourIndicatorNo").css('color', '#00192D');
    $("#stepFourIndicatorText").html('Done');
  });
  $("#fifththStepPreviousBtn").click(function(e) {
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
<!-- Display to Preview or Delete an Image before Upload -->
<script>
$(document).ready(function() {
  $("#image-selections").on("change", function() {
    var files = $(this)[0].files;
    $("#preview-container").empty();
    if (files.length > 0) {
      for (var i = 0; i < files.length; i++) {
        var reader = new FileReader();
        reader.onload = function(e) {
          $("<div class='preview' style='text-align:center;'><img class='img img-thumbnail mt-2' style='height:300px;' src='" +
            e.target.result +
            "' ><div class='deleteFileBtn mt-3'><button class='btn btn-sm' style='background-color:#cc0001; color:#fff;'><i class='fa fa-trash'></i> Delete</button></div></div>"
            ).appendTo("#preview-container");
        };
        reader.readAsDataURL(files[i]);
      }
    }
  });
  $("#preview-container").on("click", ".deleteFileBtn", function() {
    confirm('Do you want to remove this image from the List');
    $(this).parent(".preview").remove();
    $("#image-selections").val(""); // Clear input value if needed
  });
});
</script>


<!-- Add New Service Provider DOM -->
<script>
$(document).ready(function() {
  $("#firstSectionNextBtn").click(function(e) {
    e.preventDefault();
    $("#secondSection").show();
    $("#firstSection").hide();
  });

  $("#secondSectionBackBtn").click(function(e) {
    e.preventDefault();
    $("#secondSection").hide();
    $("#firstSection").show();
  });
});
</script>


<!-- Specify if the Service Provider works as an individual or as a company -->
<script>
const individualServiceProviderSection = document.getElementById("individualServiceProvider");
const companyServiceProviderSection = document.getElementById("companyServiceProvider");

function displayIndividualFreelancerDetails() {
  individualServiceProviderSection.style.display = 'block';
  companyServiceProviderSection.style.display = 'none';
}

function displayCompanyDetails() {
  companyServiceProviderSection.style.display = 'block';
  individualServiceProviderSection.style.display = 'none';
}
$(document).ready(function() {
  $("#closeIndividualSection").click(function() {
    individualServiceProviderSection.style.display = 'none';
  });

  $("#closeCompnaySection").click(function() {
    companyServiceProviderSection.style.display = 'none';
  });

});
</script>
<!-- Step by Step Buttons DOM -->
<script>
$(document).ready(function() {
  $("#firstSectionNexttBtn").click(function() {
    $("#secondSection").show();
    $("#firstSection").hide();
  });

  $("#secondSectionBackBtn").click(function() {
    $("#secondSection").hide();
    $("#firstSection").show();
  });

  $("#secondSectionNextBtn").click(function() {
    $("#secondSection").hide();
    $("#thirdSection").show();
  });

  $("#thirdSectionNextBtn").click(function() {
    $("#fourthSection").show();
    $("#thirdSection").hide();
  });

  $("#thirdSectionBackBtn").click(function() {
    $("#thirdSection").hide();
    $("#secondSection").show();
  });

  $("#fourthSectionNextBtn").click(function() {
    $("#fourthSection").hide();
    $("#fifthSection").show();
  });

  $("#fourthSectionBackBtn").click(function() {
    $("#fourthSection").hide();
    $("#thirdSection").show();
  });

  $("#fifthSectionBackBtn").click(function() {
    $("#fourthSection").show();
    $("#fifthSection").hide();
  });
});
</script>

<!-- Disable Selection of Past Dates for Leasing Starts On and Move In Date -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const leasingEndDate = document.getElementById('leasingStart');
  const moveInDate = document.getElementById('moveIn');

  // Get today's date in YYYY-MM-DD format
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
  const day = String(today.getDate()).padStart(2, '0');
  const minDate = `${year}-${month}-${day}`;

  // Set the 'min' attribute for both date inputs
  leasingEndDate.setAttribute('min', minDate);
  moveInDate.setAttribute('min', minDate);

  // Optional: Add an event listener to ensure endDate is not before startDate
  leasingEndDate.addEventListener('change', function() {
    if (leasingEndDate.value) {
      leasingEndDate.setAttribute('min', leasingEndDate.value);
    } else {
      moveInDate.setAttribute('min', minDate);
    }
  });
});
</script>

<!-- International Phone Number JavaScripts -->
<script src="plugins/intlTelInput/js/intlTelInput.min.js"></script>
<script src="plugins/intlTelInput/js/utils.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  const input = document.querySelector("#main_contact");

  window.intlTelInput(input, {
    initialCountry: "ke", // Kenya as default, change if needed
    nationalMode: false, // force international format with country code
    autoPlaceholder: "polite",
  });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const input = document.querySelector("#main_contact");

  window.intlTelInput(input, {
    initialCountry: "ke", // Kenya as default, change if needed
    nationalMode: false, // force international format with country code
    autoPlaceholder: "polite",
  });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const input = document.querySelector("#alt_contact");

  window.intlTelInput(input, {
    initialCountry: "ke", // Kenya as default, change if needed
    nationalMode: false, // force international format with country code
    autoPlaceholder: "polite",
  });
});
</script>

<!-- Validate Phone Numbers to have International Prefix before Submission -->
<script>
// Initialize intlTelInput for both fields
const mainInput = document.querySelector("#main_contact");
const altInput = document.querySelector("#alt_contact");

const itiMain = window.intlTelInput(mainInput, {
  utilsScript: "js/utils.js", // local utils.js
  initialCountry: "auto",
  geoIpLookup: function(success, failure) {
    fetch("https://ipapi.co/json")
      .then(res => res.json())
      .then(data => success(data.country_code))
      .catch(() => success("us"));
  },
});

const itiAlt = window.intlTelInput(altInput, {
  utilsScript: "js/utils.js",
  initialCountry: "auto",
  geoIpLookup: function(success, failure) {
    fetch("https://ipapi.co/json")
      .then(res => res.json())
      .then(data => success(data.country_code))
      .catch(() => success("us"));
  },
});

// Validate before form submit
document.querySelector("form").addEventListener("submit", function(e) {
  // Check Main Contact
  if (!itiMain.isValidNumber()) {
    e.preventDefault();
    Swal.fire({
      icon: 'warning',
      title: 'Invalid Main Contact',
      text: 'Please enter a valid international phone number for Main Contact.',
      confirmButtonColor: '#00192D',
      confirmButtonText: 'OK'
    });
    return;
  }

  // Check Alt Contact (only if filled)
  if (altInput.value.trim() !== "" && !itiAlt.isValidNumber()) {
    e.preventDefault();
    Swal.fire({
      icon: 'warning',
      title: 'Invalid Alternative Contact',
      text: 'Please enter a valid international phone number for Alternative Contact.',
      confirmButtonColor: '#00192D',
      confirmButtonText: 'OK'
    });
    return;
  }

  // Replace input values with full international numbers before submission
  mainInput.value = itiMain.getNumber();
  if (altInput.value.trim() !== "") {
    altInput.value = itiAlt.getNumber();
  }
});
</script>

<!-- Specify the Unit to Shift the Tenant Into whether Single, BedSitter or Multi Rooms -->
<script>
  $(document).ready(function(){
    $('input[type="radio"]').click(function(){
      var inputValue = $(this).attr("value");
      var targetBox = $("." + inputValue);
      $(".box").not(targetBox).hide();
      $(targetBox).show();
    });
    });
</script>

<!-- Tenant Security Deposits, Document Previews, Leasing Durations Handling -->
<script>
  // ==================== Security Deposits Table ====================
  function addDepositRow() {
    const tbody = document.querySelector("#paymentTable tbody");
    const row = document.createElement("tr");

    row.innerHTML = `
    <td>
      <select class="form-control depositForSelect" name="deposit_for[]">
        <option value="" disabled selected>Select option</option>
        <option value="Rent">Rent</option>
        <option value="Water">Water</option>
        <option value="Internet">Internet</option>
        <option value="Garbage">Garbage</option>
        <option value="Security">Security</option>
        <option value="Management Fee">Management Fee</option>
        <option value="Wellfare">Wellfare</option>
        <option value="Others">Others</option>
      </select>
      <input type="text" class="form-control depositForOther mt-2"
             name="deposit_for_other[]" style="display:none;" placeholder="Please specify...">
    </td>
    <td><input type="number" class="form-control requiredPay" name="required_pay[]" value="0" min="0"></td>
    <td><input type="number" class="form-control amountPaid" name="amount_paid[]" value="0" min="0"></td>

    <!-- Balance column -->
    <td class="balance">
      <input type="hidden" name="balance[]" value="0">0
    </td>

    <!-- Subtotal column -->
    <td class="subTotal">
      <input type="hidden" name="subtotal[]" value="0">0
    </td>

    <td>
      <button type="button" class="btn btn-sm removeRow"
              style="background-color:#cc0001; color:#fff;">
        <i class="bi bi-trash"></i> Remove
      </button>
    </td>
  `;

    tbody.appendChild(row);

    // Show/hide "Other" input
    const select = row.querySelector('.depositForSelect');
    const otherInput = row.querySelector('.depositForOther');
    select.addEventListener('change', function() {
      if (this.value === 'Others') {
        otherInput.style.display = '';
        otherInput.required = true;
      } else {
        otherInput.style.display = 'none';
        otherInput.required = false;
      }
      updateDepositForOptions();
    });

    // Input listeners for totals
    row.querySelectorAll(".requiredPay, .amountPaid").forEach(input => {
      input.addEventListener("input", () => {
        if (input.value < 0) input.value = 0;
        updateTableTotals();
      });
    });

    // Remove row
    row.querySelector(".removeRow").addEventListener("click", () => {
      row.remove();
      updateTableTotals();
      updateDepositForOptions();
    });

    updateTableTotals();
    updateDepositForOptions();
  }


  // Ensures deposit type options are unique across all rows
  function updateDepositForOptions() {
    const selects = document.querySelectorAll('.depositForSelect');
    // Gather all selected values except 'Others'
    const selected = Array.from(selects).map(s => s.value).filter(v => v !== 'Others');
    selects.forEach(select => {
      const currentValue = select.value;
      Array.from(select.options).forEach(option => {
        if (option.value === 'Others') {
          option.style.display = '';
        } else if (option.value === currentValue) {
          option.style.display = '';
        } else if (selected.includes(option.value)) {
          option.style.display = 'none';
        } else {
          option.style.display = '';
        }
      });
    });
  }

  //Update Table Totals
  function updateTableTotals() {
    let totalRequired = 0,
      totalPaid = 0,
      totalBalance = 0,
      totalSub = 0;

    document.querySelectorAll("#paymentTable tbody tr").forEach(row => {
      const required = parseFloat(row.querySelector(".requiredPay").value) || 0;
      const paid = parseFloat(row.querySelector(".amountPaid").value) || 0;
      const balance = Math.max(required - paid, 0);
      const sub = paid;

      // Update balance cell
      row.querySelector(".balance input").value = balance;
      row.querySelector(".balance").lastChild.nodeValue = balance;

      // Update subtotal cell
      row.querySelector(".subTotal input").value = sub;
      row.querySelector(".subTotal").lastChild.nodeValue = sub;

      totalRequired += required;
      totalPaid += paid;
      totalBalance += balance;
      totalSub += sub;
    });

    document.getElementById("totalRequired").textContent = totalRequired;
    document.getElementById("totalPaid").textContent = totalPaid;
    document.getElementById("totalBalance").textContent = totalBalance;
    document.getElementById("totalSub").textContent = totalSub;
  }


  // Shows/hides popup sections for ID mode and income type selection.
  document.querySelectorAll("input[name='idMode']").forEach(radio => {
  radio.addEventListener("change", function() {
    document.getElementById("nationalIdSection").style.display = this.value === "national" ? "block" : "none";
    document.getElementById("passportPopup").style.display = this.value === "passport" ? "block" : "none";
  });
  });


  function closePopup() {
    // Closes all popup sections for income type selection.
    document.querySelectorAll(".popup").forEach(p => p.style.display = "none");
  }

  function closeId() {
    // Validates and closes the National ID popup section.
    const idInput = document.getElementById('nationalId');
    if (!idInput.checkValidity()) {
      document.getElementById('nationalIdError').textContent = "Please enter a valid ID number.";
      return;
    }
    document.getElementById('nationalIdSection').style.display = 'none';
  }

  function closePassport() {
    // Validates and closes the Passport popup section.
    const passInput = document.getElementById('passportNumber');
    if (!passInput.checkValidity()) {
      document.getElementById('passportError').textContent = "Please enter a valid Passport number.";
      return;
    }
    document.getElementById('passportPopup').style.display = 'none';
  }

  // ==================== Leasing Dates ====================
  // ==================== Leasing Dates Calculation ====================
  // Automatically calculates lease end and move-out dates based on input values.
  document.getElementById("leasingPeriod").addEventListener("input", calculateEndDate);
  document.getElementById("leasingStart").addEventListener("change", calculateEndDate);
  document.getElementById("moveIn").addEventListener("change", calculateEndDate);

  function calculateEndDate() {
    // Calculates the leasing end date and move-out date based on leasing period and start/move-in dates.
    const months = parseInt(document.getElementById("leasingPeriod").value) || 0;
    const startDate = new Date(document.getElementById("leasingStart").value);
    const moveInDate = new Date(document.getElementById("moveIn").value);

    if (months > 0 && !isNaN(startDate)) {
      const endDate = new Date(startDate);
      endDate.setMonth(endDate.getMonth() + months);
      const iso = endDate.toISOString().split("T")[0];
      document.getElementById("leasingEnd").value = iso;
      document.getElementById("moveOut").value = iso;
    }

    // Sync move-out with move-in + months if move-in is set
    if (months > 0 && !isNaN(moveInDate)) {
      const moveOut = new Date(moveInDate);
      moveOut.setMonth(moveOut.getMonth() + months);
      document.getElementById("moveOut").value = moveOut.toISOString().split("T")[0];
    }
  }

  // ==================== Initialization ====================
  // Adds one deposit row on page load for user convenience.
  document.addEventListener("DOMContentLoaded", () => {
    addDepositRow(); // Start with one row
    updateDepositForOptions();
  });

  //Preview the Rental Agreement Document for Accuracy Purposes
  function previewPDF(input) {
    // ==================== PDF Preview ====================
    // Previews the uploaded rental agreement PDF in an iframe for accuracy.
    const file = input.files[0];
    if (file && file.type === "application/pdf") {
      const url = URL.createObjectURL(file);
      document.getElementById("pdfFrame").src = url;
      document.getElementById("pdfPreview").style.display = "block";
    } else {
      document.getElementById("pdfPreview").style.display = "none";
      document.getElementById("pdfFrame").src = "";
    }
  }

  // ==================== ID Upload Preview ====================
  function previewIdUpload(input) {
    const file = input.files[0];
    const preview = document.getElementById("idPreview");
    if (!file) {
      preview.style.display = "none";
      preview.innerHTML = "";
      return;
    }
    const ext = file.name.split('.').pop().toLowerCase();
    if (["jpg", "jpeg", "png"].includes(ext)) {
      const url = URL.createObjectURL(file);
      preview.innerHTML =
        `<img src="${url}" alt="ID Preview" style="max-width:100%; max-height:300px; border:1px solid #00192D;" class="rounded shadow">`;
      preview.style.display = "block";
    } else if (ext === "pdf") {
      const url = URL.createObjectURL(file);
      preview.innerHTML =
        `<iframe src="${url}" style="width:100%; height:300px; border:1px solid #00192D;" class="rounded shadow"></iframe>`;
      preview.style.display = "block";
    } else {
      preview.innerHTML = "<span class='text-danger'>Unsupported file type for preview.</span>";
      preview.style.display = "block";
    }
  }


  // ==================== TAX PIN Upload Preview ====================
  function previewTaxPinCopy(input) {
    const file = input.files[0];
    const preview = document.getElementById("taxPinPreview");
    if (!file) {
      preview.style.display = "none";
      preview.innerHTML = "";
      return;
    }
    const ext = file.name.split('.').pop().toLowerCase();
    if (["jpg", "jpeg", "png"].includes(ext)) {
      const url = URL.createObjectURL(file);
      preview.innerHTML =
        `<img src="${url}" alt="TAX PIN Preview" style="max-width:100%; max-height:300px; border:1px solid #00192D;" class="rounded shadow">`;
      preview.style.display = "block";
    } else if (ext === "pdf") {
      const url = URL.createObjectURL(file);
      preview.innerHTML =
        `<iframe src="${url}" style="width:100%; height:300px; border:1px solid #00192D;" class="rounded shadow"></iframe>`;
      preview.style.display = "block";
    } else {
      preview.innerHTML = "<span class='text-danger'>Unsupported file type for preview.</span>";
      preview.style.display = "block";
    }
  }
  </script>

  <!-- Validating the Identification of either ID or Passport when the user chooses one of them, there must be a filling basing on the selectin -->
  <!-- Include SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>
document.addEventListener("DOMContentLoaded", () => {
  const radios = document.querySelectorAll("input[name='idMode']");
  const nationalSection = document.getElementById("nationalIdSection");
  const passportSection = document.getElementById("passportPopup");
  const nationalInput = document.getElementById("nationalId");
  const passportInput = document.getElementById("passportNumber");
  const nationalRadio = document.getElementById("radioNational");
  const passportRadio = document.getElementById("radioPassport");

  // Handle radio change
  radios.forEach(radio => {
    radio.addEventListener("change", function() {
      if (this.value === "national") {
        nationalSection.style.display = "block";
        passportSection.style.display = "none";
        passportRadio.disabled = true; // disable passport option
      } else if (this.value === "passport") {
        passportSection.style.display = "block";
        nationalSection.style.display = "none";
        nationalRadio.disabled = true; // disable national option
      }
    });
  });
});

// Check National ID
function checkNationalId() {
  const idInput = document.getElementById("nationalId");
  const idValue = idInput.value.trim();

  if (idValue === "") {
    Swal.fire({
      icon: "warning",
      title: "Missing Information",
      text: "Please fill in the National ID Number before proceeding."
    });
  } else {
    Swal.fire({
      icon: "success",
      title: "Recorded",
      text: "National ID Number Saved Successfully.",
      timer: 1500,
      showConfirmButton: false
    });
    idInput.readOnly = true; // lock the input
    document.getElementById("nationalIdSection").style.display = "none";
  }
}

// Check Passport
function checkPassport() {
  const passInput = document.getElementById("passportNumber");
  const passValue = passInput.value.trim();

  if (passValue === "") {
    Swal.fire({
      icon: "warning",
      title: "Missing Information",
      text: "Please fill in the Passport Number before proceeding."
    });
  } else {
    Swal.fire({
      icon: "success",
      title: "Recorded",
      text: "Passport Number Saved Successfully.",
      timer: 1500,
      showConfirmButton: false
    });
    passInput.readOnly = true; // lock the input
    document.getElementById("passportPopup").style.display = "none";
  }
}
</script>

<!-- Disable Other Sources of Income when a user selects one of them -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  const incomeRadios = document.querySelectorAll("input[name='income']");

  incomeRadios.forEach(radio => {
    radio.addEventListener("change", function() {
      if (this.checked) {
        // Disable all other radios
        incomeRadios.forEach(other => {
          if (other !== this) {
            other.disabled = true;
          }
        });
      } else {
        // If unchecking (optional), re-enable all
        incomeRadios.forEach(other => {
          other.disabled = false;
        });
      }
    });
  });
});


</script>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const incomeRadios = document.querySelectorAll("input[name='income']");
  const formalPopup = document.getElementById("formalPopup");
  const casualPopup = document.getElementById("casualPopup");
  const businessPopup = document.getElementById("businessPopup");

  // Hide all popups initially
  [formalPopup, casualPopup, businessPopup].forEach(p => p.style.display = "none");

  // Handle radio selection
  incomeRadios.forEach(radio => {
    radio.addEventListener("change", function() {
      // Hide all popups first
      [formalPopup, casualPopup, businessPopup].forEach(p => p.style.display = "none");

      // Disable other radios
      incomeRadios.forEach(other => {
        if (other !== this) other.disabled = true;
      });

      // Show the appropriate popup
      if (this.value === "formal") {
        formalPopup.style.display = "block";
      } else if (this.value === "casual") {
        casualPopup.style.display = "block";
      } else if (this.value === "business") {
        businessPopup.style.display = "block";
      }
    });
  });
});

// Function to close popup after validation
function closePopup() {
  // Detect which popup is open
  const formalPopup = document.getElementById("formalPopup");
  const casualPopup = document.getElementById("casualPopup");
  const businessPopup = document.getElementById("businessPopup");

  // Formal Employment Validation
  if (formalPopup.style.display === "block") {
    const jobTitle = document.getElementById("formalWork").value.trim();
    const jobLocation = document.getElementById("formalWorkLocation").value.trim();
    if (!jobTitle || !jobLocation) {
      Swal.fire({
        icon: "warning",
        title: "Missing Information",
        text: "Please fill in both Job Title and Location before proceeding!",
        confirmButtonColor: "#00192D"
      });
      return;
    }
    formalPopup.style.display = "none";
  }

  // Casual Employment Validation
  else if (casualPopup.style.display === "block") {
    const casualJob = document.getElementById("casualWork").value.trim();
    if (!casualJob) {
      Swal.fire({
        icon: "warning",
        title: "Missing Information",
        text: "Please specify your type of casual work before proceeding!",
        confirmButtonColor: "#00192D"
      });
      return;
    }
    casualPopup.style.display = "none";
  }

  // Business Validation
  else if (businessPopup.style.display === "block") {
    const businessName = document.getElementById("businessName").value.trim();
    const businessLocation = document.getElementById("businessLocation").value.trim();
    if (!businessName || !businessLocation) {
      Swal.fire({
        icon: "warning",
        title: "Missing Information",
        text: "Please provide both Business Name and Location before proceeding!",
        confirmButtonColor: "#00192D"
      });
      return;
    }
    businessPopup.style.display = "none";
  }

  // Once valid, lock all radios (prevent changes)
  document.querySelectorAll("input[name='income']").forEach(radio => {
    radio.disabled = true;
  });

  Swal.fire({
    icon: "success",
    title: "Saved Successfully",
    text: "Your income source has been recorded.",
    timer: 1500,
    showConfirmButton: false
  });
}
</script>

<!-- Add tenant Form Validation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
  e.preventDefault(); // Stop form submission
  const form = e.target;
  let valid = true;
  let message = '';

  // Helper to show error
  function showError(msg, fieldId = null) {
    Swal.fire({
      icon: 'error',
      title: 'Missing Information',
      text: msg,
      confirmButtonColor: '#00192D'
    }).then(() => {
      if (fieldId) document.getElementById(fieldId)?.focus();
    });
  }

  // --- Validate Text Inputs ---
  const requiredInputs = [
    { id: 'first_name', msg: 'Please enter your First Name.' },
    { id: 'tmiddle_name', msg: 'Please enter your Middle Name.' },
    { id: 'last_name', msg: 'Please enter your Last Name.' },
    { id: 'main_contact', msg: 'Please enter your Main Contact number.' },
    { id: 'email', msg: 'Please enter your Email address.' },
    { id: 'leasingPeriod', msg: 'Please enter Leasing Period.' },
    { id: 'leasingStart', msg: 'Please select Leasing Start Date.' },
    { id: 'moveIn', msg: 'Please select Move In Date.' },
  ];

  for (let input of requiredInputs) {
    const field = document.getElementById(input.id);
    if (!field || field.value.trim() === '') {
      valid = false;
      message = input.msg;
      showError(message, input.id);
      return; // stop further checking
    }
  }

  // --- Validate ID/Passport ---
  const national = document.getElementById('radioNational').checked;
  const passport = document.getElementById('radioPassport').checked;
  const nationalId = document.getElementById('nationalId').value.trim();
  const passportNum = document.getElementById('passportNumber').value.trim();

  if (!national && !passport) {
    valid = false;
    showError('Please select a mode of identification (National ID or Passport).');
    return;
  }

  if (national && nationalId === '') {
    valid = false;
    showError('Please fill in your National ID Number.', 'nationalId');
    return;
  }

  if (passport && passportNum === '') {
    valid = false;
    showError('Please fill in your Passport Number.', 'passportNumber');
    return;
  }

  // --- Validate Uploads ---
  const idUpload = document.getElementById('id_upload');
  const taxPinCopy = document.getElementById('tax_pin_copy');
  const rentalAgreement = document.getElementById('rental_agreement');

  if (!idUpload.value) {
    valid = false;
    showError('Please upload your Identification document.', 'id_upload');
    return;
  }
  if (!taxPinCopy.value) {
    valid = false;
    showError('Please upload your TAX PIN copy.', 'tax_pin_copy');
    return;
  }
  if (!rentalAgreement.value) {
    valid = false;
    showError('Please upload your Rental Agreement.', 'rental_agreement');
    return;
  }

  // --- Validate Income Source ---
  const incomeRadios = document.querySelectorAll('input[name="income"]');
  const selectedIncome = Array.from(incomeRadios).some(r => r.checked);
  if (!selectedIncome) {
    valid = false;
    showError('Please select your main source of income.');
    return;
  }

  // --- If All Valid ---
  if (valid) {
    Swal.fire({
      icon: 'success',
      title: 'Validation Successful',
      text: 'All required fields are filled. Submitting form...',
      showConfirmButton: false,
      timer: 1500
    }).then(() => {
      form.submit(); // proceed to submit
    });
  }
});
</script>

<!-- Disable Cut, Copy and Paste in the Form Fields -->
<script>
const restrictedFields = {
  nationalId: "National ID Number",
  passportNumber: "Passport Number",
  main_contact: "Main Contact Number",
  alt_contact: "Alternative Contact Number",
  email: "Email Address",
  tax_pin_copy: "Tax PIN Upload",
  id_upload: "ID Upload",
  first_name: "First Name",
  tmiddle_name: "Middle Name",
  last_name: "Last Name",
  leasingPeriod: "Leasing Period",
  formalWork: "Formal Work",
  formalWorkLocation: "Work Location",
  casualWork: "Casual Work",
  businessName: "Business Name",
  businessLocation: "Business Location"
};

// Loop through each restricted field
Object.keys(restrictedFields).forEach(id => {
  const field = document.getElementById(id);
  if (field) {
    ['cut', 'copy', 'paste'].forEach(action => {
      field.addEventListener(action, e => {
        e.preventDefault();

        let actionText = '';
        if (action === 'cut') actionText = 'Cutting';
        if (action === 'copy') actionText = 'Copying';
        if (action === 'paste') actionText = 'Pasting';

        Swal.fire({
          icon: 'warning',
          title: `${actionText} Not Allowed`,
          text: `${actionText} ${restrictedFields[id]} is not permitted.`,
          confirmButtonColor: '#00192D',
          timer: 3500
        });
      });
    });
  }
});
</script>



<script>
// -------------------- FILE SIZE VALIDATION --------------------
document.addEventListener('DOMContentLoaded', function() {
  const maxFileSize = 2 * 1024 * 1024; // 2 MB limit

  // All file input elements
  const fileInputs = [
    { id: 'id_upload', label: 'Identification Upload' },
    { id: 'tax_pin_copy', label: 'TAX PIN Upload' },
    { id: 'rental_agreement', label: 'Rental Agreement Upload' }
  ];

  fileInputs.forEach(input => {
    const element = document.getElementById(input.id);
    if (element) {
      element.addEventListener('change', function() {
        if (this.files.length > 0) {
          const file = this.files[0];
          if (file.size > maxFileSize) {
            Swal.fire({
              icon: 'warning',
              title: 'File too large!',
              html: `${input.label} must not exceed <b>2MB</b>.<br>Your file: <b>${(file.size / (1024 * 1024)).toFixed(2)} MB</b>`,
              confirmButtonColor: '#00192D'
            });
            this.value = ''; // clear invalid file
          }
        }
      });
    }
  });
});
</script>

<!----------------------------- Meter Readings Script ----------------------------->
<script>
document.addEventListener("DOMContentLoaded", () => {

    const readingDate = document.getElementById("reading_date");
    const previousReading = document.getElementById("previous_reading");
    const currentReading = document.getElementById("current_reading");
    const unitsConsumed = document.getElementById("units_consumed");
    const costPerUnit = document.getElementById("cost_per_unit");
    const finalBill = document.getElementById("final_bill");

    // 1️⃣ Disable selection of past dates
    const today = new Date().toISOString().split("T")[0];
    readingDate.setAttribute("min", today);

    // 2️⃣ Function to calculate units and bill
    function calculateBill() {
        const prev = parseFloat(previousReading.value) || 0;
        const curr = parseFloat(currentReading.value) || 0;
        const cost = parseFloat(costPerUnit.value) || 0;

        // Validate: previous cannot be greater than current
        if (prev > curr) {
            Swal.fire({
                icon: "error",
                title: "Invalid Readings",
                text: "Previous reading cannot be greater than current reading!",
            }).then(() => {
                currentReading.value = "";
                unitsConsumed.value = "";
                finalBill.value = "";
                currentReading.focus();
            });
            return;
        }

        // Compute units consumed
        const units = curr - prev;
        unitsConsumed.value = units > 0 ? units.toFixed(2) : "0.00";

        // Compute final bill
        const bill = units * cost;
        finalBill.value = bill > 0 ? bill.toFixed(2) : "0.00";
    }

    // 3️⃣ Event listeners
    previousReading.addEventListener("input", calculateBill);
    currentReading.addEventListener("input", calculateBill);
    costPerUnit.addEventListener("input", calculateBill);

});
</script>

<!-- Handle Tenant Invoices -->
<script>
    // --- Existing Invoice Date and Due Date Logic ---
    const invoiceDate = document.getElementById("invoiceDate");
    const dueDate = document.getElementById("dateDue");
    const today = new Date().toISOString().split("T")[0];
    invoiceDate.setAttribute("min", today);
    invoiceDate.addEventListener("change", function() {
        const d = new Date(this.value);
        d.setDate(d.getDate() + 5);
        dueDate.value = d.toISOString().split("T")[0];
    });

    // --- Invoice Item Management Variables ---
    const allItems = ["Internet", "Electricity", "Garbage", "Penalty", "Parking", "Maintenance", "Security", "Welfare", "Fumigation", "Others"];
    const invoiceBody = document.getElementById("invoiceBody");
    const addItemDrawer = document.getElementById("addItemDrawer");
    const addItemDrawerBackdrop = document.getElementById("addItemDrawerBackdrop");
    const addItemForm = document.getElementById("addItemForm");
    const drawerItemName = document.getElementById("drawerItemName");
    const drawerOtherInput = document.getElementById("drawerOtherInput");
    const drawerDescription = document.getElementById("drawerDescription");
    const drawerUnitPrice = document.getElementById("drawerUnitPrice");
    const drawerQuantity = document.getElementById("drawerQuantity");
    const drawerTaxType = document.getElementById("drawerTaxType");

    // --- Helper Functions ---
    function formatCurrency(num) {
        return num.toLocaleString("en-KE", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function generateInvoiceNumber() {
        const d = new Date();
        const datePart = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, "0")}-${String(d.getDate()).padStart(2, "0")}`;
        let stored = JSON.parse(localStorage.getItem("invoiceCounter")) || {};
        let counter = 1;
        if (stored.date === datePart) {
            counter = stored.counter + 1;
        }
        stored = { date: datePart, counter: counter };
        localStorage.setItem("invoiceCounter", JSON.stringify(stored));
        return `INV-${datePart}-${String(counter).padStart(3, "0")}`;
    }

    function getSelectedItems() {
        return Array.from(document.querySelectorAll("#invoiceBody .itemName"))
            .map(sel => sel.value)
            .filter(v => v && v !== "Others" && v !== "Rent" && v !== "Water"); // Exclude fixed items from selection pool
    }

    function buildItemOptionsForDrawer(currentValue = "") {
        const selected = getSelectedItems();
        let options = `<option value="">-- Select Item --</option>`;
        allItems.forEach(item => {
            if (item === currentValue || item === "Others" || !selected.includes(item)) {
                options += `<option value="${item}" ${item === currentValue ? 'selected' : ''}>${item}</option>`;
            }
        });
        return options;
    }

    // Function specifically for the drawer's "Paid For" dropdown
    function checkDrawerOthersInput(select) {
        if (select.value === "Others") {
            drawerOtherInput.classList.remove("d-none");
            drawerOtherInput.required = true;
        } else {
            drawerOtherInput.classList.add("d-none");
            drawerOtherInput.required = false;
            drawerOtherInput.value = "";
        }
    }

    // --- Offcanvas Drawer Control ---
    function openAddItemDrawer() {
        // Reset form fields before opening
        addItemForm.reset();
        drawerItemName.innerHTML = buildItemOptionsForDrawer(); // Populate dropdown
        checkDrawerOthersInput(drawerItemName); // Hide/show "Other" input
        drawerUnitPrice.value = 0;
        drawerQuantity.value = 1;
        drawerTaxType.value = "VAT Inclusive";

        addItemDrawer.classList.add("show");
        addItemDrawerBackdrop.classList.add("show");
        document.body.style.overflow = 'hidden'; // Prevent main page scroll
    }

    function closeAddItemDrawer() {
        addItemDrawer.classList.remove("show");
        addItemDrawerBackdrop.classList.remove("show");
        document.body.style.overflow = ''; // Restore main page scroll
        addItemForm.reset(); // Clear form on close
    }

    // --- Add Item from Drawer to Table ---
    addItemForm.addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent default form submission

        // Validate drawer form fields (required attributes handle most)
        if (!addItemForm.checkValidity()) {
            addItemForm.reportValidity(); // Show native browser validation messages
            return;
        }

        const itemNameValue = drawerItemName.value;
        const otherInputValue = drawerOtherInput.value.trim();
        let finalItemName = itemNameValue;
        if (itemNameValue === "Others" && otherInputValue) {
            finalItemName = `Others - ${otherInputValue}`;
        } else if (itemNameValue === "Others" && !otherInputValue) {
             Swal.fire("Error", "Please specify the 'Others' item.", "error");
             return;
        }

        const descriptionValue = drawerDescription.value.trim();
        const unitPriceValue = parseFloat(drawerUnitPrice.value);
        const quantityValue = parseInt(drawerQuantity.value);
        const taxTypeValue = drawerTaxType.value;

        // Calculate initial tax and total for the new row
        let tax = 0;
        let total = 0;
        if (taxTypeValue === "VAT Inclusive") {
            let priceWithoutTax = unitPriceValue / 1.16;
            tax = (unitPriceValue - priceWithoutTax) * quantityValue;
            total = unitPriceValue * quantityValue;
        } else if (taxTypeValue === "VAT Exclusive") {
            tax = unitPriceValue * 0.16 * quantityValue;
            total = (unitPriceValue * quantityValue) + tax;
        } else {
            total = unitPriceValue * quantityValue;
        }

        const row = document.createElement("tr");
        row.innerHTML = `
            <td>
                <input type="hidden" class="itemName" value="${finalItemName}">
                ${finalItemName}
            </td>
            <td><input type="hidden" class="description" value="${descriptionValue}">${descriptionValue}</td>
            <td><input type="hidden" class="unitPrice" value="${unitPriceValue.toFixed(2)}">${formatCurrency(unitPriceValue)}</td>
            <td><input type="hidden" class="quantity" value="${quantityValue}">${quantityValue}</td>
            <td><input type="hidden" class="taxType" value="${taxTypeValue}">${taxTypeValue}</td>
            <td class="taxAmount">${formatCurrency(tax)}</td>
            <td class="totalPrice">${formatCurrency(total)}</td>
            <td><button type="button" onclick="this.closest('tr').remove(); updateTotals();"
                class="btn btn-sm text-white shadow" style="background-color:#cc0001;">Remove</button></td>`;
        invoiceBody.appendChild(row);

        closeAddItemDrawer(); // Close the drawer after adding
        updateTotals(); // Recalculate all totals
    });


    // --- Original updateTotals function (modified to use hidden inputs for values) ---
    function updateTotals() {
        let subtotal = 0;
        let totalTax = 0;
        let finalTotal = 0;

        document.querySelectorAll("#invoiceBody tr").forEach(row => {
            const isFixedRow = row.querySelector(".itemName[data-fixed='true']");

            // For dynamic rows added via drawer, values are in hidden inputs
            // For fixed rows, values are in direct inputs
            const itemNameElement = row.querySelector(".itemName");
            const descriptionElement = row.querySelector(".description");
            const unitPriceElement = row.querySelector(".unitPrice");
            const quantityElement = row.querySelector(".quantity");
            const taxTypeElement = row.querySelector(".taxType");


            const unitPrice = parseFloat(unitPriceElement.value) || 0;
            const qty = parseInt(quantityElement.value) || 0;
            const taxType = taxTypeElement.value;

            let tax = 0;
            let total = 0;

            if (taxType === "VAT Inclusive") {
                let priceWithoutTax = unitPrice / 1.16;
                tax = (unitPrice - priceWithoutTax) * qty;
                total = unitPrice * qty;
            } else if (taxType === "VAT Exclusive") {
                tax = unitPrice * 0.16 * qty;
                total = (unitPrice * qty) + tax;
            } else { // Zero Rated or Exempted
                total = unitPrice * qty;
            }

            subtotal += unitPrice * qty;
            totalTax += tax;
            finalTotal += total;

            // Update displayed tax amount and total price in the table cells
            row.querySelector(".taxAmount").textContent = formatCurrency(tax);
            row.querySelector(".totalPrice").textContent = formatCurrency(total);
        });

        document.getElementById("subtotal").textContent = formatCurrency(subtotal);
        document.getElementById("totalTax").textContent = formatCurrency(totalTax);
        document.getElementById("finalTotal").textContent = formatCurrency(finalTotal);

        document.getElementById("subtotalValue").value = subtotal.toFixed(2);
        document.getElementById("totalTaxValue").value = totalTax.toFixed(2);
        document.getElementById("finalTotalValue").value = finalTotal.toFixed(2);
    }

    // --- prepareInvoiceData for form submission ---
    function prepareInvoiceData() {
        const rows = document.querySelectorAll("#invoiceBody tr");
        if (rows.length === 0) {
            Swal.fire("Error", "Please add at least one invoice item.", "error");
            return false;
        }
        const items = [];
        rows.forEach(row => {
            // Retrieve values from hidden inputs
            items.push({
                item_name: row.querySelector(".itemName").value,
                description: row.querySelector(".description").value,
                unit_price: parseFloat(row.querySelector(".unitPrice").value),
                quantity: parseInt(row.querySelector(".quantity").value),
                tax_type: row.querySelector(".taxType").value,
                tax_amount: parseFloat(row.querySelector(".taxAmount").textContent.replace(/,/g, "")) || 0,
                total_price: parseFloat(row.querySelector(".totalPrice").textContent.replace(/,/g, "")) || 0
            });
        });
        document.getElementById("invoiceItems").value = JSON.stringify(items);
        return true;
    }

    // --- DOMContentLoaded for initial Rent/Water rows ---
    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("invoiceNumber").value = generateInvoiceNumber();

        const tbody = document.getElementById("invoiceBody");
        const monthlyRent = parseFloat(document.getElementById("monthlyRent").value) || 0;
        const finalBill = parseFloat(document.getElementById("finalBill").value) || 0;

        // Add Rent row if applicable
        if (monthlyRent > 0) {
            const rentRow = document.createElement("tr");
            rentRow.innerHTML = `
                <td>
                    <input type="hidden" class="itemName" value="Rent" data-fixed="true">
                    Rent
                </td>
                <td><input type="hidden" class="description" value="Monthly Rent">Monthly Rent</td>
                <td><input type="hidden" class="unitPrice" value="${monthlyRent.toFixed(2)}">${formatCurrency(monthlyRent)}</td>
                <td><input type="hidden" class="quantity" value="1">1</td>
                <td>
                    <input type="hidden" class="taxType" value="VAT Inclusive">VAT 16% Inclusive
                </td>
                <td class="taxAmount">${formatCurrency(monthlyRent / 1.16 * 0.16)}</td> <!-- Recalculate VAT for display -->
                <td class="totalPrice">${formatCurrency(monthlyRent)}</td>
                <td><button type="button" class="btn btn-sm text-white shadow" style="background-color:gray;" disabled>Fixed</button></td>`;
            tbody.appendChild(rentRow);
        }

        // Add Water row if applicable
        // ... (Rent logic remains above) ...

        // Add Water row if applicable
        if (finalBill > 0) {
            const waterRow = document.createElement("tr");
            waterRow.innerHTML = `
                <td>
                    <input type="hidden" class="itemName" value="Water" data-fixed="true">
                    Water
                </td>
                <td><input type="hidden" class="description" value="Water Bill">Water Bill</td>
                <td><input type="hidden" class="unitPrice" value="${finalBill.toFixed(2)}">${formatCurrency(finalBill)}</td>
                <td><input type="hidden" class="quantity" value="1">1</td>
                <td>
                    <!-- CHANGED: Set value to Exempted and label to Exempted -->
                    <input type="hidden" class="taxType" value="Exempted">Exempted
                </td>
                <!-- CHANGED: Hardcoded tax amount to 0.00 -->
                <td class="taxAmount">0.00</td> 
                <td class="totalPrice">${formatCurrency(finalBill)}</td>
                <td><button type="button" class="btn btn-sm text-white shadow" style="background-color:gray;" disabled>Fixed</button></td>`;
            tbody.appendChild(waterRow);
        }
        
        updateTotals(); // Call updateTotals after adding all initial rows
        updateTotals(); // Call updateTotals after adding all initial rows
    });
</script>