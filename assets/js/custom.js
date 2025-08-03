jQuery(function(){
  new DataTable('#tbl-student-table');

  //Employee form validation
  jQuery("#sms-form-add-student").validate();

  //To upload media on Add student page
  jQuery('#btn-upload-profile').on("click", function(event){
    event.preventDefault();

    //Create media instance
    let mediaUploader = wp.media({
      title:"Select profile image",
      multiple: false
    });

    //Select Image Handle function
    mediaUploader.on("select",function(){
      let attachment = mediaUploader.state().get("selection").first().toJSON();
      //console.log(attachment);
      jQuery("#profile_url").val(attachment.url);
    });

    //Open media model
    mediaUploader.open();
  });

  //On Deactivate event
  jQuery("#deactivate-student-management-system").on("click", function(event){
    event.preventDefault();
    var booleanValue = confirm("Are you sure want to deactivate 'Student Management System'?");
    if(booleanValue){
      window.location.href = jQuery(this).attr("href");
    }
  });
});
