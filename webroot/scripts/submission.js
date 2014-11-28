$(function() {

var id = $('select[name="medium"]').val();
var the_file;
var old_file;
var displayIt = $('img.profile-picture').attr('alt');


  $(window).load(function(){
    $(".spotlights").mCustomScrollbar({
      theme: 'dark'});
    $(".submissions").mCustomScrollbar({
      theme: 'dark'});
  });

$('select[name="rate"]').on('change', function() {
    var value = $(this).val();
    var subId = $(this).data('submission-id');
    if (value != 'Rate it') {
    $.ajax({
        url: 'ajaxRequest.php',
        type: 'POST',
        data: {
            rating: value,
            id: subId,
            type: 'rate-it'
        },
        success: function() {
        $('p.notice').animate({opacity: '0.0'}, 0, function() {
          $('p.notice').animate({opacity: '1.0'}, 500);}  );
        }
    });
    }
});

$('#uploader').on('change', function(event) {
    var files = event.target.files;
    var ext = this.value.match(/\.(.+)$/)[1];
    var fd = new FormData();
    $.each(files, function(key, value)
    {
        fd.append(key, value);
    });
    var ext = files[0]['name'].match(/\.(.+)$/)[1];
    fd.append('type', 'temp_submission');
    fd.append('extension', ext);
  $.ajax({ 
    url: 'ajaxRequest.php',
    type: 'post',
    processData: false,
    contentType: false,
    cache: false,
    data: fd,
    success: function(data) {
      console.log(data);
      the_file = 'submissions/image/tmp/'+data;
    },
    error: function() {console.log('nope')}
    })
});

$('.send-information').on('click', function() {
  var genres = '';
  
  $('input[type="checkbox"]').each(function() {
    if ($(this).is(':checked')) {
        genres += $(this).val()+',';
    }
    });
    genres = genres.substr(0, genres.length - 1);
  if(id == '1') {
    $.ajax({
        url: 'ajaxRequest.php',
        type: 'POST',
        data: {
            type: 'newImageSubmission',
            file: the_file,
            title: $('input[name="title"]').val(),
            mediumID: id,
            genre: genres,
            tag: $('input[name="tags"]').val()
        },
        success: function(data) {
            location.replace('http://www.konfirmed.com/temp/webroot/submission.php?id='+data);
        }
    });
  }
  // if it's a motherfucking video
  if(id == '2') {
    $.ajax({
        url: 'ajaxRequest.php',
        type: 'POST',
        data: {
            type: 'newVideoSubmission',
            title: $('input[name="title"]').val(),
            url: $('input[name="videoUrl"]').val(),
            mediumID: id,
            genre: genres,
            tag: $('input[name="tags"]').val()
        },
        success: function(data) {
            if(data == "wrong url") {
              alert("wrong url format, please follow the guideline");            
            }
            else {
                location.replace('http://www.konfirmed.com/temp/webroot/submission.php?id='+data);
            }
        }
    });
  }
  //audio
  if(id == '4') {
    $.ajax({
        url: 'ajaxRequest.php',
        type: 'POST',
        data: {
            type: 'newAudioSubmission',
            title: $('input[name="title"]').val(),
            url: $('input[name="AudioUrl"]').val(),
            mediumID: id,
            genre: genres,
            tag: $('input[name="tags"]').val()
        },
        success: function(data) {
            if(data == "wrong url") {
              alert("wrong url format, please follow the guideline");            
            }
            location.replace('http://www.konfirmed.com/temp/webroot/submission.php?id='+data);
        }
    });
  }
    
  //text
  if(id == '3') {
    $.ajax({
        url: 'ajaxRequest.php',
        type: 'POST',
        data: {
            type: 'newTextSubmission',
            title: $('input[name="title"]').val(),
            text: $('textarea[name="textBody"]').val(),
            mediumID: id,
            genre: genres,
            tag: $('input[name="tags"]').val()
        },
        success: function(data) {
            if(data == "wrong url") {
              alert("wrong url format, please follow the guideline");            
            }
            location.replace('http://www.konfirmed.com/temp/webroot/submission.php?id='+data);
        }
    });
  }
});

//on change medium get the genres

$('select[name="medium"]').on('change', function() {
  var medium = $(this);
  id = medium.val();
  $.ajax({
    url: 'ajaxRequest.php',
    type: 'POST',
    data: {
      type: 'getMediumGenres',
      mediumID: id
    },
    success: function(data) {
      $('div.genres-box').html(data);
      //also change the preview to allow various format.
      if(id == "2") {
        $('.the-stuff').html('<input type="text" name="videoUrl" class="submission-input " placeholder="enter the video url" autocomplete="off">');
        $('.the-stuff').removeClass('center');
      }
      else if(id == "1") {
        $('.the-stuff').html('Please choose a profile picture: <input type="file" name="uploadFile" id="uploader"><br>');
        $('.preview').css({'background': 'none'}).html('');
        $('.the-stuff').removeClass('center');
        $('.the-stuff').addClass('center');
      }
      else if(id == "3") {
        $('.the-stuff').html('<textarea name="textBody" class="text-zone"></textarea>');
        $('.the-stuff').removeClass('center');
        $('.the-stuff').addClass('center');  
      }
      else if(id == "4") {
        $('.the-stuff').html('<input type="text" name="AudioUrl" class="submission-input " placeholder="Enter the audio URL" autocomplete="off">');
        $('.the-stuff').removeClass('center');
      }
    }
  });
});


$('.delete').on('click', function() {
  var to_delete = $(this);
  $.ajax({
    url: 'ajaxRequest.php',
    type: 'POST',
    data: {
      type: 'deleteSubmission',
      id: $(this).data('submission-id')
    },
    success: function(data) {
      console.log(data);
      if(data == 'success') {
        to_delete.closest('p').hide(500).remove();    
      }
    }
    
 });
 
  });
  
  /** icons and menu **/
 
    $('div.box').on('click', function() {
      var category = $(this).attr('class').split(' ')[0];
      var selectedID;
      switch (category) {
        case "audio":
            selectedID = '4';
            break;
        case "image":
            selectedID = '1';
            break;
        case "video":
            selectedID = '2';
            break;
        case "text":
            selectedID = '3';
            break;          
      }            

       $.ajax({
          url: 'ajaxRequest.php',
          type: 'POST',
          data: {
           mediumID: selectedID,
           type: 'iconPopulator'
           },
           success: function(data) {
            $('.selected-category').html(data);
           $(".spotlights").mCustomScrollbar({
               theme: 'dark'});
           $(".submissions").mCustomScrollbar({
               theme: 'dark'});
           }
      });

      $('.selected-category').addClass(category);
      $('.selected-category').animate({left: '0'}, 500);      
      });
    $('.selected-category').on('click', '.close-selected-category', function() {
      var category = $('.selected-category').attr('class').split(' ')[1];
      $('.selected-category').animate({left: '-100%'}, 500).queue(function() {
        $('.selected-category').removeClass(category);
        $('.selected-category').html('');
        $(this).dequeue(); });
      });
          
    $('.remove-it').on('click', function() {
    $.ajax({
        url: 'ajaxRequest.php',
        type: 'POST',
        data: {
            type: 'remove-submission',
            id: $('.remove-it').data('id')
        },
        success: function(data) {
            location.reload();
        }
    });
  });
  
    $('.spotlight-it').on('click', function() {
    $.ajax({
        url: 'ajaxRequest.php',
        type: 'POST',
        data: {
            type: 'insert-submission',
            id: $('.spotlight-it').data('id')
        },
        success: function(data) {
            location.reload();
        }
    });
  });
  
});