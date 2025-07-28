document.addEventListener('DOMContentLoaded', () => {
    const sidebarContainer = document.getElementById('sidebar-container');
    const mainContentArea = document.getElementById('main-content-area');

    // Views
    const dashboardView = document.getElementById('dashboard-view');
    const detailsView = document.getElementById('details-view');
    const formView = document.getElementById('form-view');
    const notificationsView = document.getElementById('notifications-view');
    const profileView = document.getElementById('profile-view');
    const documentsView = document.getElementById('documents-view');

    const allViews = document.querySelectorAll('.content-view');

    // Links
    const dashboardLink = document.getElementById('link-dashboard');
    const notificationsLink = document.getElementById('link-notifications');
    const profileLink = document.getElementById('link-profile');
    const documentsLink = document.getElementById('link-documents');

    // Form Handling
    const formTabsContainer = formView.querySelector('.tabs');
    const formTabContents = formView.querySelectorAll('.tab-content');
    const applyingToTitle = formView.querySelector('#applying-to-title');

    // Profile Picture
    const avatarInput = document.getElementById('profile-pic-input');
    const avatarPreview = document.getElementById('edit-profile-pic');

    // Central view controller
    function showView(viewToShow) {
        allViews.forEach(view => {
            view.classList.remove('active-view');
        });
        if (viewToShow) {
            viewToShow.classList.add('active-view');
        }
        mainContentArea.scrollTop = 0;
    }

    // Handle form tab clicks
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

    // Sidebar navigation
    dashboardLink?.addEventListener('click', e => {
        e.preventDefault();
        showView(dashboardView);
    });

    notificationsLink?.addEventListener('click', e => {
        e.preventDefault();
        showView(notificationsView);
    });

    profileLink?.addEventListener('click', e => {
        e.preventDefault();
        showView(profileView);
    });

    documentsLink?.addEventListener('click', e => {
        e.preventDefault();
        showView(documentsView);
    });

    // Main content area actions (view details, apply)
    mainContentArea.addEventListener('click', event => {
        if (event.target.matches('.view-details-btn')) {
            event.preventDefault();
            const scholarshipId = event.target.getAttribute('data-scholarship-id');
            console.log(`Viewing details for: ${scholarshipId}`);
            showView(detailsView);

        } else if (event.target.matches('.apply-now-btn')) {
            event.preventDefault();
            console.log("Apply Now clicked");
            const programTitle = detailsView.querySelector('.scholarship-info .scholarship-meta strong') ?
                detailsView.querySelector('.scholarship-info .scholarship-meta strong').parentNode.textContent.split('\n')[0].replace('Status: ', '') :
                "Selected Scholarship";
            if (applyingToTitle) applyingToTitle.textContent = programTitle;

            showView(formView);
            if (formTabsContainer) handleTabClick(formTabsContainer.querySelector('.tab-button'));

        } else if (event.target.matches('.tab-button')) {
            event.preventDefault();
            handleTabClick(event.target);

        } else if (event.target.matches('.next-button')) {
            event.preventDefault();
            const currentActiveTab = formTabsContainer.querySelector('.tab-button.active');
            const nextTab = currentActiveTab?.nextElementSibling;
            if (nextTab && nextTab.matches('.tab-button')) {
                handleTabClick(nextTab);
            } else {
                console.log("End of form or submit logic needed.");
            }
        }
    });

    // Handle profile picture upload
    avatarInput?.addEventListener('change', event => {
        const file = event.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = e => {
                avatarPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Document remove buttons
    document.querySelectorAll('.remove-btn').forEach(button => {
        button.addEventListener('click', () => {
            alert('Document removed.');
        });
    });

    // Default view
    showView(dashboardView);
    if (formTabsContainer) handleTabClick(formTabsContainer.querySelector('.tab-button'));
});
