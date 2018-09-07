$(document).ready(function () {
  engageSelect2();
  loadSpanishMonths();
  loadDatePickers();
  handleNumeric();
});

function loadDatePickers() {
  $('.datetime').datepicker({
    format: 'mm/dd/yyyy',
    language: curLocale
  });
}

function handleNumeric() {
  $(".numeric").numeric();
  $(".integer").numeric(false, function () {
    alert("Integers only");
    this.value = "";
    this.focus();
  });
  $(".positive").numeric({negative: false}, function () {
    alert("No negative values");
    this.value = "";
    this.focus();
  });
  $(".positive-integer").numeric({decimal: false, negative: false}, function () {
    alert("Positive integers only");
    this.value = "";
    this.focus();
  });
  $(".decimal-2-places").numeric({decimalPlaces: 2});
  $(".alternative-decimal-separator").numeric({altDecimal: ","});
  $(".alternative-decimal-separator-reverse").numeric({altDecimal: ".", decimal: ","});

}

function loadSpanishMonths() {
  $.fn.datepicker.dates['es'] = {
    days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"],
    daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vier", "Sáb", "Dom"],
    daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa", "Do"],
    months: ["Enero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
    monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dec"],
    today: "Hoy"
  };
}

function loadCartHeader() {
  jQuery.ajax({
    url: '/product/cart/header',
    type: "GET",
    cache: false,
  }).done(function (data) {
    $('#cartHeader').html(data);
  });
}

function setModalFormValues(title) {
  if (title == "fei.productForm") {
    action = "/admin/product";
    formName = "productForm";
    $("#modalForm").attr('enctype', 'multipart/form-data');
  }
  if (title == "fei.binProductForm") {
    action = "/admin/bin/items/save";
    formName = "binProductForm";
    $("#modalForm").attr('enctype', 'multipart/form-data');
  }
  $("#modalForm").attr('action', action);
  $("#modalForm").attr('onsubmit', "return " + formName + "_validate()");

}

/**
 onkeypress="return IntegersOnly(event)"
 */
function IntegersOnly(evt) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    return false;
  }
  return true;
}

function engageSelect2() {
  $('.select2').select2({
    minimumResultsForSearch: 5
  });

}

function logout() {
  document.location = "/logout";
}

function setLang(lang) {
  url = "/setLang/" + lang + '?r=' + encodeURIComponent(document.location);
  document.location = url;
}

function validatePageAndSubmit(endPoint, params, formId) {
  $('#mainBody').hide();
  $('#waitingBody').show();
  jQuery.ajax({
    url: endPoint,
    type: "POST",
    data: params,
    cache: false,
  }).done(function (data) {
    $('#waitingBody').hide();
    $('#mainBody').show();
    if (data.trim() == '') {
      document.getElementById(formId).submit();
    } else {
      alert(data);
    }
  });
}

function companyForm_validate() {
  companyForm_checkForm();
  return false;
}

function companyForm_submit() {
  document.getElementById('companyFormSubmit').click();
}

function companyForm_checkForm() {
  validatePageAndSubmit('/admin/company/v', $('#companyForm').serialize(), 'companyForm');
}

function userForm_validate() {
  userForm_checkForm();
  return false;
}

function userForm_submit() {
  document.getElementById('userFormSubmit').click();
}

function userForm_checkForm() {
  validatePageAndSubmit('/admin/user/v', $('#userForm').serialize(), 'userForm');
}

function modalLoading() {
  $('#modalBody').html("<img src=\"/images/ajax_loader.gif\" style=\"width: 100px\"/>");
  $('#modalTitle').html('');
  $('#modalFormId').val('')
}

function modalContent(content) {
  $('#modalBody').html(content);
}

function modalAjax(title, method, endPoint, params) {
  modalLoading();
  $('#myModal').modal({
    keyboard: true
  });
  setModalFormValues(title);
  $('#modalFormId').val(title)
  jQuery.ajax({
    url: '/translate',
    type: "GET",
    data: {c: title},
    cache: true,
  }).done(function (data) {
    $('#modalTitle').html(data);
  });

  jQuery.ajax({
    url: endPoint,
    type: method,
    data: params,
    cache: false,
  }).done(function (data) {
    modalContent(data);
    $('select').select2();
  });
}

function productForm(companyId, itemId) {
  endPoint = "/admin/product/" + companyId + "/" + itemId;
  title = "fei.productForm";
  modalAjax(title, 'GET', endPoint, []);
}

function productForm_validate() {
  $('#image_v').val($('#image').val());
  validatePageAndSubmit('/admin/product/v', $('#modalForm').serialize(), 'modalForm');
  return false;
}


function binForm_generateCode() {
  code = ' '
  for (x = 0; x < 12; x++) {
    y = Math.floor(Math.random() * 10);
    code = code + y;
  }
  code = code.trim();
  endPoint = '/admin/bin/vcode/' + $('#bin_id').val() + '/' + code;

  jQuery.ajax({
    url: endPoint,
    type: 'GET',
    cache: false,
  }).done(function (data) {
    if (data == 0) {
      binForm_generateCode();
    } else {
      $('#code').val(data);
    }
  });
}

function binForm_validate() {
  validatePageAndSubmit('/admin/bin/v', $('#binForm').serialize(), 'binForm');
  return false;
}

function productInventoryForm(companyId, itemId) {
  endPoint = "/admin/bin/items/product/form/" + companyId + "/" + itemId;
  title = "fei.binProductForm";
  modalAjax(title, 'GET', endPoint, []);
}

function binProductForm_validate() {

  alert($('#modalForm').serialize());
  validatePageAndSubmit('/admin/bin/product/v', $('#modalForm').serialize(), 'modalForm');
  return false;
}

function putLodingInDiv(divId) {
  $('#' + divId).html("<img src=\"/images/ajax_loader.gif\" style='width: 50px'/>");
}

function loadHomeProducts() {

  putLodingInDiv('productList');
  jQuery.ajax({
    url: '/product/list/' + $('[name="company_id"]').val(),
    type: "GET",
    cache: false,
  }).done(function (data) {
    $('#productList').html(data);
  });

}

function imageModalShow(id) {

  // width = Math.round(.80 * ($(document).width()));
  // height = Math.round(.80 * ($(document).height()));
  // $('#myImageModalContainer').attr('style', 'width: ' + width + 'px; ;height: '  + height + 'px;');

  imgUrl = $('#productImage_' + id).attr('src');
  title = $('#productTitle_' + id).text();

  $('#imageModalTitle').html(title);
  $('#modalImage').attr('src', imgUrl);
  $('#myImageModal').modal({
    keyboard: true
  });
}

function validateAddCart(id, qty) {
  qtyOnHand = $('#qtyOnHand_' + id).val();
  if (parseInt(qty) > parseInt(qtyOnHand)) {
    alert($('#cannotOrderThatMany').val());
    return false;
  }
  return true;
}

function addCart(id) {
  qty = $('#productinput_' + id).val();
  if (validateAddCart(id, qty)) {

    params = "id=" + id + "&qty=" + qty;
    endPoint = '/product/cart/save';
    jQuery.ajax({
      url: endPoint,
      type: "POST",
      data: params,
      cache: false,
    }).done(function (data) {
      location.reload();
    });
  }

}


function copyFromShipping() {
  arr = [
    "first_name", "last_name", "company", "address1", "address2", "address3",
    "city", "state", "zip", "country", "phone1", "phone2", "fax", "email"
  ];
  for (x = 0; x < arr.length; x++) {
    $("[name='to_" + arr[x] + "']").val($("[name='from_" + arr[x] + "']").val());
  }
}

function checkoutForm_checkForm() {
  validatePageAndSubmit('/checkout/v', $('#checkoutForm').serialize(), 'checkoutForm');
}

function checkoutForm_validate() {
  checkoutForm_checkForm();
  return false;
}

function validateFinalizeFulfillment() {
  totalsMustEqual = $("[name='totalsMustEqual']").val()
  itemList = $("[name='itemList']").val().split(',');
  errs = [];
  for (x = 0; x < itemList.length; x++) {
    itemId = itemList[x];
    itemDescr = $("[name='descr_" + itemId + "']").val();
    totalCount = $("[name='total_" + itemId + "']").val();
    inputCount = 0;
    i = 0;
    while (true) {
      i++;
      id = "[name='item_" + itemId + "_" + i + "']";
      if ($(id).length == 0) {
        break;
      }
      inputCount += parseInt($(id).val());
    }
    if (inputCount != totalCount) {
      errs.push(itemDescr + '\n' + totalsMustEqual + " " + inputCount + " != " + totalCount);
    }
  }
  if (errs.length > 0) {
    alert(errs.join("\n\n"));
    return false;
  }
  return true;

}

function finalizeFulfillment() {

  if (validateFinalizeFulfillment() && confirm($('#confirmationFinalize').val())) {
    return true;
  }
  else {
    return false;
  }

}

var numTracking = 0;

function addTracking(trackingVal, trackingId, shipperId, costVal) {

  numTracking++;
  trackingNumber = $('#trackingNumber').val();
  shipper = $('#shipper').val();
  cost = $('#cost').val();


  id = "tracking_" + numTracking;
  $("[name='num_tracking']").val(numTracking);
  html = " <div class=\"col-md-6\">\n" +
    "\n <input type='hidden' name='id_" + id + "' value='" + trackingId + "'/>" +
    "            <div class=\"form-group\">\n" +
    "                <label for=\"" + id + "\">" + shipper + "</label>\n" +
    buildTrackingShipper(numTracking, shipperId) +
    "            </div>" +
    "            <div class=\"form-group\">\n" +
    "                <label for=\"" + id + "\">" + trackingNumber + "</label>\n" +
    "                <input class=\"form-control required\" id=\"" + id + "\" name=\"" + id + "\"\n" +
    "                       value=\"" + trackingVal + "\"/>\n" +
    "            </div>" +
    "            <div class=\"form-group\">\n" +
    "                <label for=\"" + id + "\">" + cost + "</label>\n" +
    "                <input class=\"form-control decimal-2-places required\" name=\"cost_" + numTracking + "\"\n" +
    "                       value=\"" + costVal + "\"/>\n" +
    "            </div><hr/>" +
    " </div>";
  $('#trackingDiv').append(html);
  $('.select2').select2();
  handleNumeric();
}

function buildTrackingShipper(numTracking, value) {
  // alert("Value: " + value);
  var shippersOptions = JSON.parse(document.getElementById('shipperJson').value);
  html = "<select name='shipper_" + numTracking + "' class='select2' style='width:100%'>"
  for (var key in shippersOptions) {
    selection = '';
    if (key == value) {
      selection = ' selected="selected" ';
    }
    // alert(key +"=="+  value + ":" + selection);
    option = '<option value="' + key + '"' + selection + '>' + shippersOptions[key] + '</option>';
    html += option;
  }
  html += '</select>';
  return html;
}


function cancelOrder(orderId) {
  if (confirm($('#areYouSure').val())) {
    document.location = '/order/cancel/' + orderId;
  }
}


function selectAll(className) {
  $('.' + className).prop('checked', true);
}

function formatNumber2(n) {
  return parseFloat(Math.round(n * 100) / 100).toFixed(2);
}

function calculateInvoiceFormTotals() {
  shipTotal = 0;
  handleTotal = 0;
  otherTotal = 0;
  for (x = 0; x < $('#rowCount').val(); x++) {
    orderId = $('#order_id_' + x).val();
    shipping = (getNumericVal('shipping_' + orderId));
    handle = (getNumericVal('handling_' + orderId));
    other = (getNumericVal('other_' + orderId));

    shipTotal += shipping;
    handleTotal += handle;
    otherTotal += other;
    total = shipping + handle + other;

    $('[name="total_' + orderId + '"]').val(formatNumber2(total));
  }
  totalTotal = shipTotal + handleTotal + otherTotal;
  $('[name="shipping_total"]').val(formatNumber2(shipTotal));
  $('[name="handling_total"]').val(formatNumber2(handleTotal));
  $('[name="other_total"]').val(formatNumber2(otherTotal));
  $('[name="total_total"]').val(formatNumber2(totalTotal));
}

function getNumericVal(objName) {
  v = $('[name="' + objName + '"').val();
  if (isNaN(v) || v == '') {
    v = 0;
  }
  v = parseFloat(v);
  return v;

}
