document.addEventListener('DOMContentLoaded', () => {
   
    const sidebarContainer = document.getElementById('sidebar-container');
    const mainContentArea = document.getElementById('main-content-area');


    const dashboardView = document.getElementById('dashboard-view');
    const detailsView = document.getElementById('details-view');
    const formView = document.getElementById('form-view');
    const allViews = [dashboardView, detailsView, formView];

    
    const dashboardLink = document.getElementById('link-dashboard');
    const notificationsLink = document.getElementById('link-notifications');
    const profileLink = document.getElementById('link-profile');

    const formTabsContainer = formView.querySelector('.tabs');
    const formTabContents = formView.querySelectorAll('.tab-content');
    const applyingToTitle = formView.querySelector('#applying-to-title');
    const avatarInput = formView.querySelector('#avatar-upload-input');
    const avatarPreview = formView.querySelector('#main-profile-pic'); 
    const sidebarAvatarPreview = sidebarContainer.querySelector('#sidebar-avatar-preview'); 


    function showView(viewToShow) {
        allViews.forEach(view => {
            if (view === viewToShow) {
                view.classList.add('active-view');
            } else {
                view.classList.remove('active-view');
            }
        });
        mainContentArea.scrollTop = 0;
    }

    if (dashboardLink) {
        dashboardLink.addEventListener('click', (e) => {
            e.preventDefault();
            showView(dashboardView);
        });
    }
    if (notificationsLink) {
         notificationsLink.addEventListener('click', (e) => {
             e.preventDefault();
             console.log("Notifications clicked - View not implemented yet");
         });
    }
     if (profileLink) {
         profileLink.addEventListener('click', (e) => {
             e.preventDefault();
             console.log("My Profile clicked - View not implemented yet");
              
         });
     }
    



    mainContentArea.addEventListener('click', (event) => {

        if (event.target.matches('.view-details-btn')) {
            event.preventDefault();
            const scholarshipId = event.target.getAttribute('data-scholarship-id');
            console.log(`Viewing details for: ${scholarshipId}`);
          
            showView(detailsView);
            
        }

      
        else if (event.target.matches('.apply-now-btn')) {
            event.preventDefault();
            console.log("Apply Now clicked");
            
            const programTitle = detailsView.querySelector('.scholarship-info .scholarship-meta strong') ? detailsView.querySelector('.scholarship-info .scholarship-meta strong').parentNode.textContent.split('\n')[0].replace('Status: ','') : "Selected Scholarship"; // Simple way to grab title
            if(applyingToTitle) applyingToTitle.textContent = programTitle;

            showView(formView);
            if(formTabsContainer) handleTabClick(formTabsContainer.querySelector('.tab-button'));
        }

       
        else if (event.target.matches('.tab-button')) {
            event.preventDefault();
            handleTabClick(event.target);
        }

        
         else if (event.target.matches('.next-button')) {
             event.preventDefault();
             console.log("Next button clicked");
              const currentActiveTab = formTabsContainer.querySelector('.tab-button.active');
              const nextTab = currentActiveTab ? currentActiveTab.nextElementSibling : null;
              if (nextTab && nextTab.matches('.tab-button')) {
                   handleTabClick(nextTab);
               } else {
                   console.log("End of form or submit logic needed.");
                  
               }
         }
    });

  

    
    function handleTabClick(clickedButton) {
        if (!clickedButton) return;
         
        formTabsContainer.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active');
        });
        clickedButton.classList.add('active');

        
        const targetSelector = clickedButton.getAttribute('data-tab-target');
        formTabContents.forEach(content => {
            if (`#${content.id}` === targetSelector) {
                content.classList.add('active-tab-content');
            } else {
                content.classList.remove('active-tab-content');
            }
        });
    }

   
     if (avatarInput && avatarPreview && sidebarAvatarPreview) {
         avatarInput.addEventListener('change', event => {
             const file = event.target.files[0];
             if (file && file.type.startsWith('image/')) {
                 const reader = new FileReader();
                 reader.onload = (e) => {
                     const imageUrl = e.target.result;
                     avatarPreview.src = imageUrl;
                     sidebarAvatarPreview.src = imageUrl; 
                 }
                 reader.readAsDataURL(file);
             } else {
                 
                 const placeholder = 'placeholder-profile.png'; 
                 avatarPreview.src = placeholder;
                 sidebarAvatarPreview.src = placeholder; 
             }
         });
     }


   
    showView(dashboardView); 
    
    if(formTabsContainer) handleTabClick(formTabsContainer.querySelector('.tab-button'));


}); 