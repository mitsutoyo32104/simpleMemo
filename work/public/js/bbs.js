$(function() {

  $('#search-title').click(function() {
    $('#search-form').slideToggle(500);

    if($('#search-button').is('[src=../image/button-plus.png]')) {
      $('#search-button').attr('src', '../image/button-minus.png');
    } 
    else {
      $('#search-button').attr('src', '../image/button-plus.png');
    }
  });


  $('#post-title').click(function() {
    $('#post-form').slideToggle(500); 

    if($('#newpost-button').is('[src=../image/button-plus.png]')) {
      $('#newpost-button').attr('src', '../image/button-minus.png');
    } 
    else {
      $('#newpost-button').attr('src', '../image/button-plus.png');
    }
  });

  
  $('#delete-title').click(function() {
    $('#delete-form').slideToggle(500); 

    if($('#delete-button').is('[src=../image/button-plus.png]')) {
    $('#delete-button').attr('src', '../image/button-minus.png');
    } 
    else {
    $('#delete-button').attr('src', '../image/button-plus.png');
    }
  });


  document.getElementById("indicate").addEventListener('click', () => {
    const passwordNode = document.getElementsByName('password')[0];
    const passwordIndicateNode = document.getElementById('indicate');

    if(passwordIndicateNode.checked === true) {
      passwordNode.setAttribute('type', 'text');
    } else {
      passwordNode.setAttribute('type', 'password');
    }
  });

  // 連動プルダウン
  $("#main-category").val("仕事");
  genreChange();
  
  $("#main-category").change(genreChange);

  function genreChange() {
    let mainCategory = $('#main-category').val();
    
    var parameter = "main-category=" + mainCategory;

    $.ajax({
      url: "bbs_category.php",
      data: parameter,
      dataType: "json",
      cache: false,
      success: successFunction,
      error: errorFunction
    });

    function successFunction (data, status) {
      $("#sub-category").empty();
      for (let i = 0; i < data.length; i++) {
        let name = data[i]['name'];
        let option = $("<option>");
        option.text(name);
        $("#sub-category").append(option);
      }  
    }

    function errorFunction (data) {
      window.alert(JSON.stringify(data));
    }
  }  
});