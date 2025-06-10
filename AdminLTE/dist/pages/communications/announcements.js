
                                                    // Overlays/ PopUps (SECTION 1)

const   more_announcement = document.getElementById('more_announcement_btn');
  const   view_announcement = document.getElementById('view_announcement');
  const   close_overlay = document.getElementById("close-overlay-btn");

  more_announcement.addEventListener('click', ()=>{

     view_announcement.style.display= "flex";
    
     view_announcement.style.display= "flex";
    //  document.querySelector('.app-wrapper').style.opacity = '0.3'; // Reduce opacity of main content
     const now = new Date();
            const formattedTime = now.toLocaleString(); // Format the date and time
            timestamp.textContent = `Sent on: ${formattedTime}`;


  });

     close_overlay.addEventListener('click', ()=>{

     view_announcement.style.display= "none";
    //  document.querySelector('.app-wrapper').style.opacity = '1';


     });


    // new announcement 
    const   create_announcement= document.getElementById('Create_Announcement');
    const   announcement_window = document.getElementById('New_Announcement');
    const   close_overlayOne = document.getElementById("close-overlay-btnOne");
  
    create_announcement.addEventListener('click', ()=>{
  
      announcement_window.style.display= "flex";
       document.querySelector('.app-wrapper').style.opacity = '0.3'; // Reduce opacity of main content
       const now = new Date();
              const formattedTime = now.toLocaleString(); // Format the date and time
              timestamp.textContent = `Sent on: ${formattedTime}`;
  
  
    });
  
       close_overlay.addEventListener('click', ()=>{
  
       view_announcement.style.display= "none";
      //  document.querySelector('.app-wrapper').style.opacity = '1';
  
  
       });


    //  DataTable (SECTION 2)
