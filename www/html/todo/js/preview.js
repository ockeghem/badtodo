function isValidUrl(url) {
  try {
    new URL(url);
    return true;
  } catch (err) {
    return false;
  }
}
function preview() {
  const url = $("#input-url").val()
  const preview = $("#preview")
  if (!url) {
    preview.css({ 'display': 'none' })
    preview.html('')
  } else if (!isValidUrl(url)) {
    preview.css({ 'display': 'block' })
    preview.html('<p>URLが不正です:' + url + '</p>')
  } else {
    $.ajax({
      url: "api/v1/proxy.php",
      type: "get",
      data: { "url": url },
      dataType: "html",
    }).done(function (result) {
      const customConfig = {
        ALLOWED_TAGS: ['h1', 'h2', 'h3', 'h4', 'p', 'a', 'b', 'i', 'em', 'strong', 'span', 'div', 'br', 'hr'],
        FORBID_ATTR: ['href']
      }
      const cleanHTML = DOMPurify.sanitize(result, customConfig)
      preview.css({ 'display': 'block' })
      preview.html(cleanHTML)
      if (! $('#input-linktext').val()) {
        const title = result.match(/<title>(.*)<\/title>/)
        $('#input-linktext').val(title[1])
      }
    }).fail(function () {
      preview.css({ 'display': 'block' })
      preview.html('<p>コンテンツが見つかりまぜん</p>')
    })
  }
}
$(function () {
  $("#input-url").change(function () {
    preview()
  })
  preview()
})
