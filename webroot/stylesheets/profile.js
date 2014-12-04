$(function() {


var $stuff = $('.content-wrapper');

var $container = $('.content').masonry({
    itemSelector: '.content-wrapper',
    transitionDuration: 0
});

$stuff.each(function() {
    $(this).imagesLoaded( function() {
      console.log("yes ");
      $container.masonry();
    });
});

// initialize

  $.getScript("scripts/topnav.js");
  $.getScript("scripts/left.js");

//scroller

$(".wrapper").perfectScrollbar({
    suppressScrollX: true,
    includePadding: true
});

// or with jQuery
var $container = $('#container');
// initialize Masonry after all images have loaded  
$container.imagesLoaded( function() {
  $container.masonry();
});
  
  var latest = $('.content-wrapper').last().data("id");
  var account = $('div.profile-info').data("account-id");
  var stopIt = false;
  
  if($('.content-wrapper').length) {
  
      setInterval(function() {
        var calculate = $(window).height() - $('.content-wrapper').last().offset().top;
        if(calculate > 300 && !stopIt) {
          $.ajax({
            url: 'ajaxRequest.php',
            type: 'POST',
            data: {
              type: 'getSubmissions',
              start: latest,
              async: false,
              id: account },
            beforeSend: function() {stopIt = true;},
            success: function(data) {
             // for(i = 0; i < 3; i++) {
             //   elems.push($parser[i]);
             //   console.log($parser[i]);
            //  }
              $('.content').append(data);
                $stuff.each(function() {
                    $(this).imagesLoaded( function() {
                     $('.content').masonry('reloadItems');
                     $('.content').masonry('layout');
                    });
                });
              var new_latest = $('.content-wrapper').last().data("id");  
              if(new_latest > latest) {
              latest = new_latest;
              stopIt = false;
              }
              else {
              console.log('yeah');
              stopIt = true;}
            }
          });    
       } 
      }, 500);
  }
  
  
     $('div.box-2').on('click', function() {
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
        $(this).dequeue(); });
      });

      $('.tab').on('click', function() {
        var $data = $(this).data('tab');
        console.log($data);
        if($data == 'about') {
            $('.about').css({display: 'flex'});
            $('.timeline').hide(0);
        }
        else if($data == 'timeline') {
            $('.about').hide(0);
            $('.timeline').show(0);
            $('.content').masonry('reloadItems');
            $('.content').masonry('layout');
        }
    });
    
    $('.edit').on('click', function() {
        var $data = $(this).next('span.'+$(this).data('edit'));
        if($(this).data('edit') == 'fname-edit') {
            $data.html('<input type="text" class="edit fname" value="'+$data.data('fname')+'"/>');
            $('input.fname').focus();
        }
        if($(this).data('edit') == 'lname-edit') {
            $data.html('<input type="text" class="edit lname" value="'+$data.data('lname')+'"/>');
            $('input.lname').focus();
        }
        else if($(this).data('edit') == 'location-edit') {
            $data.html('<input type="text" class="edit location" value="'+$data.data('location')+'"/>');
            $('input.location').focus();
        }
        else if($(this).data('edit') == 'artist-edit') {
            $data.html('<input type="text" class="edit artist" value="'+$data.data('artist')+'"/>');
            $('input.artist').focus();
        }
    });
    
    $('img.banner-edit').on('click', function() {
        $('#banner-uploader').trigger('click');
    });
    
    $('img.picture-edit').on('click', function() {
        $('#profile-uploader').trigger('click');
    });

    $('img.bio-edit').on('click', function() {
        var $data = $('span.bio-edit');
        $data.html('<textarea class="bio-edit">'+$data.data('bio')+'</textarea>');
        $data.append('<button class="bio-edit">Edit Bio</button>');
    });
    
    $('img.twitter-edit').on('click', function() {
        var $data = $('span.twitter-edit');
        $data.html('twitter.com/<input type="text" class="twitter" value="'+$data.data('twitter')+'"/>');
        $('input.twitter').focus();
    });
    
    $('img.facebook-edit').on('click', function() {
        var $data = $('span.facebook-edit');
        $data.html('facebook.com/<input type="text" class="facebook" value="'+$data.data('facebook')+'"/>');
        $('input.facebook').focus();
    });
    
    $('img.website-edit').on('click', function() {
        var $data = $('span.website-edit');
        $data.html('http://<input type="text" class="website" value="'+$data.data('website')+'"/>');
        $('input.website').focus();
    });
    
    $('span.website-edit').on('blur', 'input', function() {
        var $data = $('span.website-edit');
            $.ajax({
                url: 'ajaxRequest.php',
                type: 'POST',
                data: {
                    type: 'updateOneValue',
                    id: $('div.about').data('id'),
                    columnName: 'website',
                    value: $('input.website').val()
                },
                success: function(data) {
                    $data.data('website', data);
                    $data.html('<a href="http://'+$data.data('website')+'" class="website-edit">website</a>');
                    $('p.website').html('<a href="http://'+$data.data('website')+'" class="website-edit">website</a>');
                }
            });
    });  
    
    $('span.twitter-edit').on('blur', 'input', function() {
        var $data = $('span.twitter-edit');
            $.ajax({
                url: 'ajaxRequest.php',
                type: 'POST',
                data: {
                    type: 'updateOneValue',
                    id: $('div.about').data('id'),
                    columnName: 'twitter',
                    value: $('input.twitter').val()
                },
                success: function(data) {
                    $data.data('twitter', data);
                    $data.html('<a href="https://twitter.com/'+$data.data('twitter')+'" class="twitter-edit">Twitter</a>');
                    $('p.twitter').html('<a href="https://twitter.com/'+$data.data('twitter')+'" class="twitter-edit">Twitter</a>');
                }
            });
    });
    
    $('span.facebook-edit').on('blur', 'input', function() {
        var $data = $('span.facebook-edit');
            $.ajax({
                url: 'ajaxRequest.php',
                type: 'POST',
                data: {
                    type: 'updateOneValue',
                    id: $('div.about').data('id'),
                    columnName: 'facebook',
                    value: $('input.facebook').val()
                },
                success: function(data) {
                    $data.data('facebook', data);
                    $data.html('<a href="https://www.facebook.com/'+$data.data('facebook')+'" class="facebook-edit">Facebook</a>');
                    $('p.facebook').html('<a href="https://www.facebook.com/'+$data.data('facebook')+'" class="facebook-edit">Facebook</a>');
                }
            });
    });
    
    $('span').on('blur', 'input', function() {
        var $data = $(this).closest('span');
        if($data.data('fname') != undefined) {
            $.ajax({
                url: 'ajaxRequest.php',
                type: 'POST',
                data: {
                    type: 'updateOneValue',
                    id: $('div.about').data('id'),
                    columnName: 'fname',
                    value: $('input.fname').val()
                },
                success: function(data) {
                    $data.data('fname', data);
                    $data.html($data.data('fname'));
                    $('p.profile-name').html($data.data('fname') + " " + $('span.lname-edit').data('lname'));
                }
            });
        }
        
        if($data.data('lname') != undefined) {
            $.ajax({
                url: 'ajaxRequest.php',
                type: 'POST',
                data: {
                    type: 'updateOneValue',
                    id: $('div.about').data('id'),
                    columnName: 'lname',
                    value: $('input.lname').val()
                },
                success: function(data) {
                    $data.data('lname', data);
                    $data.html($data.data('lname'));
                    $('p.profile-name').html($('span.fname-edit').data('fname') + " " + $data.data('lname'));
                }
            });
        }
        
        if($data.data('location') != undefined) {
            $.ajax({
                url: 'ajaxRequest.php',
                type: 'POST',
                data: {
                    type: 'updateOneValue',
                    id: $('div.about').data('id'),
                    columnName: 'location',
                    value: $('input.location').val()
                },
                success: function(data) {
                    $data.data('location', data);
                    $data.html($data.data('location'));
                    $('p.location').html($data.data('location'));
                }
            });
        }   

        if($data.data('artist') != undefined) {
            $.ajax({
                url: 'ajaxRequest.php',
                type: 'POST',
                data: {
                    type: 'updateOneValue',
                    id: $('div.about').data('id'),
                    columnName: 'occupation',
                    value: $('input.artist').val()
                },
                success: function(data) {
                    $data.data('artist', data);
                    $data.html($data.data('artist'));
                    $('p.artist').html($data.data('artist'));
                }
            });
        }  
        
    });
    
    $('span.bio-edit').on('click', 'button.bio-edit', function(e) {
        var $data = $('span.bio-edit');
            $.ajax({
                url: 'ajaxRequest.php',
                type: 'POST',
                data: {
                    type: 'updateOneValue',
                    id: $('div.about').data('id'),
                    columnName: 'bio',
                    value: $('textarea.bio-edit').val()
                },
                success: function(data) {
                    $data.data('bio', data);
                    $data.html($data.data('bio')); 
                }
            });
          
    });
    
    //image upload
    
    var the_file;
    var displayIt = $('img.profile-picture').attr('alt');
    
     $('#profile-uploader').on('change', function(event) {
      var files = event.target.files;
      var ext = this.value.match(/\.(.+)$/)[1];
      var fd = new FormData();
      $.each(files, function(key, value)
      {
        fd.append(key, value);
      });
      fd.append('type', 'setProfileImage');
      var ext = files[0]['name'].match(/\.(.+)$/)[1];
      $.ajax({ 
        url: 'ajaxRequest.php',
        type: 'post',
        processData: false,
        contentType: false,
        cache: false,
        data: fd,
        success: function(data) {
          console.log(data);
          the_file = 'images/profile/'+data;
          $('img.profile-picture').attr('src', 'images/profile/'+data+"?"+Math.floor((1000*Math.random()))).data('src', '');
        },
        error: function() {console.log('nope')}
        })
    });
    
     $('#banner-uploader').on('change', function(event) {
      var files = event.target.files;
      var ext = this.value.match(/\.(.+)$/)[1];
      var fd = new FormData();
      $.each(files, function(key, value)
      {
        fd.append(key, value);
      });
      fd.append('type', 'setBannerImage');
      var ext = files[0]['name'].match(/\.(.+)$/)[1];
      $.ajax({ 
        url: 'ajaxRequest.php',
        type: 'post',
        processData: false,
        contentType: false,
        cache: false,
        data: fd,
        success: function(data) {
          console.log(data);
          the_file = 'images/banner/'+data;
          $('div.banner').attr('style', 'background: url("images/banner/'+data+"?"+Math.floor((1000*Math.random()))+'") no-repeat top center; background-size: auto 100%;').data('src', '');
          $('img.banner-placeholder').remove();
        },
        error: function() {console.log('nope')}
        })
    });
                           
});