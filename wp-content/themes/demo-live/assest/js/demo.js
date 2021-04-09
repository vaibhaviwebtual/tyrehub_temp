
(function(){
  $('.carousel-showmanymoveone .item').each(function(){
    var itemToClone = $(this);

    for (var i=1;i<6;i++) {
      itemToClone = itemToClone.next();

      // wrap around if at end of item collection
      if (!itemToClone.length) {
        itemToClone = $(this).siblings(':first');
      }

      // grab item, clone, add marker class, add to collection
      itemToClone.children(':first-child').clone()
        .addClass("cloneditem-"+(i))
        .appendTo($(this));
    }
  });


  /*Location_section Tabs*/
  $(document).ready(function()
  {

      $('ul.tabs li').click(function(){
      var tab_id = $(this).attr('data-tab');

      $('ul.tabs li').removeClass('current');
      $('.tab-content').removeClass('current');

      $(this).addClass('current');
      $("#"+tab_id).addClass('current');
      })

      $('.select-lang').on('change',function(){
        var lang = $(this).val();
        var select_lang = '.select-lang-content .'+lang;
        $('.select-lang-content .language-content').css('display','none');
        $(select_lang).css('display','block');

      });


      $('.installer-tab').click(function(){
          var tab = $(this).attr('data-tab');
          var selected_class = '.'+tab;
          $('.installer-tab').removeClass('active');
          $(this).addClass('active');
          $('.installer-tab-content').removeClass('active');
          $(selected_class).addClass('active');

      });


  })
  /*Location_section Tabs*/
}());



