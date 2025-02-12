
jQuery(document).ready(function($){
 
    function load_unseen_notification(view = '') { 
	    var dispute_url = $('#fnehd-front-user-noty').data('fnehd-dispute-url');
	    var data = {'action':'fnehd_notifications', 'fnehd_dispute_url':dispute_url, 'noty-action':view,};
	
	    jQuery.post(fnehd.ajaxurl, data, function(response) {   
	   
           $('.notif-content').html(response.noty);
           if(response.unseen_noty > 0){
	          $('.fnehd-notification').addClass(response.noty_bg_class);	 
              $('.fnehd-notification').html(response.unseen_noty);
            }
     
        });
	
   }
 
   load_unseen_notification();
 
   $('body').on('click', '.fnehd-view-notif', function(){
      $('.fnehd-notification').removeClass('bg-secondary bg-primary');		  
      $('.fnehd-notification').html('');
      load_unseen_notification('viewed');
   });
 
 
});