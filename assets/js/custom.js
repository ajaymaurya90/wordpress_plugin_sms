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

  // Student add form submission through Ajax Request
  jQuery("#btn_sms_form").on("click", function(event){
    event.preventDefault();
    
    var formData = jQuery("#frm_sms_form").serialize() + "&action=sms_ajax_handler&param=save_form";

    //var formData = "action=sms_ajax_handler&param=save_form&name=ajay&email=ajay.maurya@gmail.com&designation=developer";
    jQuery.ajax({
      url: sms_ajax_url,
      data: formData,
      method: "POST",
      success: function(response){
        if(response.status){
          toastr.success(response.message);
        }else{
          toastr.error(response.message);
        }
        //to relaod the page after 2 second
          setTimeout(function(){
            location.reload()
          }, 2000);
        
      },
      error: function(response){
        console.log(response);
        //for error response
      }
    });
  });

  //to call the function load students only on listing page 
  if(jQuery("#tbl-student-table").length > 0){
    load_student();
  }
});

function load_student(){
  var formData = "action=sms_ajax_handler&param=load_students";
  var studentHtml = "";

  jQuery("#tbl-student-table").DataTable().destroy();

  jQuery.ajax({
    url: sms_ajax_url,
    data: formData,
    method: "GET",
    success: function(response){
      if(response.status){
        //We have student
        jQuery.each(response.data, function(index, student){
            studentHtml += "<tr>";
          studentHtml += "<td>"+ student.id +"</td>";
          studentHtml += '<td><img src="'+ student.profile_img+ '"style="height:60px"></td>';
          studentHtml += "<td>"+ student.name +"</td>";
          studentHtml += "<td>"+ student.email +"</td>";
          studentHtml += "<td>"+ student.gender +"</td>";
          studentHtml += "<td>"+ student.phone_no +"</td>";
          studentHtml += "<td>"+ student.created_at +"</td>";
          studentHtml += '<td><a href="admin.php?page=student-system&action=view&id='+student.id+'" class="btn-view">View</a><a href="admin.php?page=student-system&action=edit&id='+student.id+'" class="btn-edit">Edit</a><a href="" class="btn-delete btn-student-delete" data-id="'+student.id+'">Delete</a></td>';
          studentHtml += "</tr>";
        });
        jQuery("#tbl-student-table tbody").html(studentHtml);
        jQuery("#tbl-student-table").DataTable();
      }else{
        //No student found
      }

      
      console.log(response);
    },
    error: function(response){

    }
  });
}
