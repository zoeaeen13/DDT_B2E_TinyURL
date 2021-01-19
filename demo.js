const form = $('.form');
const BASE_URL = 'handleUrl.php';

function showLink(link) {
  const linkHTML = `您縮短的網址為：<span>${link}</span>`;
  $('.form input').val('');
  $('.submit-result').html(linkHTML);
  $('.error-message').addClass('hidden');
  $('.submit-result').removeClass('hidden');
}

function setErrorMessage(error) {
  $('.error-message').text(error);
  $('.error-message').removeClass('hidden');
}

//init
let searchCode = null;
const re = /#.+/;
if (window.location.href.match(re)) {
  searchCode = window.location.href.match(re)[0].replace('#', '');
  $.ajax({
    method: 'GET',
    dataType: 'json',
    url: `${BASE_URL}?code=${searchCode}`,
  }).done((resp) => {
    console.log(resp);
    if (!resp.data) {
      return setErrorMessage('找不到該網址或系統忙碌中');
    }
    // redirect
    $(location).attr('href', resp.data);
  })
}

// submit
$('.form').submit((e) => {
  e.preventDefault();

  const inputURL = $('.form input').val();
  $.ajax({
    type: 'POST',
    dataType: 'json',
    url: BASE_URL,
    data: {
      url: inputURL,
    },
  }).done((resp) => {
    if (!resp.ok) {
      return setErrorMessage(resp.message);
    }
    const link = 'http://oldfish.tw/tinyurl/' + '#' + resp.data;
    showLink(link);
  })
});