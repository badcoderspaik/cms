$(function () {
  var form_notification = new APP.Widget.Notification.CheckForm({
      width: "400px",
      color: "yellow",
      fontSize: "larger",
      boxShadow: "-5px 5px 10px gray"
    }),

    form_data_loader = new APP.FormDataLoader({
      url: "../admin/modules/add_content_form.php",
      form_element: $("#add_content_form"),
      success: function (content) {
        form_notification.content(content);
        form_notification.hide();
      },
      beforeSend: function () {
        form_notification.show("Обработка данных...");
      }
    });

  form_notification.append();

});
