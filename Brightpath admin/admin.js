document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-item');
    const pages = document.querySelectorAll('.page');
    const settingsContent = document.getElementById('settings-content');

    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            const pageId = this.getAttribute('data-page');

            navLinks.forEach(navLink => navLink.classList.remove('active'));
            pages.forEach(page => page.classList.remove('active-page'));

            this.classList.add('active');
            document.getElementById(pageId).classList.add('active-page');

            //  Load enquiries table when settings page is clicked
            if (pageId === 'settings') {
                loadEnquiriesTable();
            }
        });
    });

    document.getElementById('add-account-btn').addEventListener('click', function() {
        alert('Add account functionality goes here.');
    });

    document.getElementById('add-scholarship-btn').addEventListener('click', function() {
        alert('Add scholarship functionality goes here.');
    });

    document.getElementById('save-settings-btn').addEventListener('click', function() {
        const siteTitle = document.getElementById('site-title').value;
        alert(`Site title saved as: ${siteTitle}`);
    });

    //  Settings JS functions

    function loadEnquiriesTable() {
        const enquiriesHTML = `
            <div class="enquiries-container">
                <div class="enquiries-header">
                    <div class="enquiries-title">All Enquiries</div>
                    <div class="enquiries-filters">
                        <input type="text" placeholder="dd-mm-yyyy">
                        <select>
                            <option>Select Status</option>
                            <option>COMPLETED</option>
                            <option>PENDING</option>
                        </select>
                        <button class="filter-button">FILTER</button>
                        <button class="reset-button">RESET</button>
                    </div>
                </div>
                <table class="enquiries-table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Batch</th>
                            <th>Phone</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>ved</td>
                            <td>2514534412</td>
                            <td>Tony Stark</td>
                            <td><span class="status-completed">COMPLETED</span></td>
                            <td>
                                <button class="view-button" onclick="viewEnquiry(1)">VIEW</button>
                                <button class="delete-button" onclick="deleteEnquiry(1)">DELETE</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>om</td>
                            <td>321343412</td>
                            <td>Pepper Pots</td>
                            <td><span class="status-pending">PENDING</span></td>
                            <td>
                                <button class="view-button" onclick="viewEnquiry(2)">VIEW</button>
                                <button class="delete-button" onclick="deleteEnquiry(2)">DELETE</button>
                            </td>
                        </tr>
                        </tbody>
                </table>
            </div>
        `;
        settingsContent.innerHTML = enquiriesHTML;

        //  Fetch and populate table data (example)
        fetchEnquiriesData();
    }

    function fetchEnquiriesData() {
        //  Replace with your actual API endpoint
        fetch('get_enquiries.php')
            .then(response => response.json())
            .then(data => {
                populateEnquiriesTable(data);
            })
            .catch(error => console.error('Error fetching enquiries:', error));
    }

    function populateEnquiriesTable(enquiries) {
        const tableBody = document.querySelector('.enquiries-table tbody');
        tableBody.innerHTML = ''; // Clear existing rows

        enquiries.forEach(enquiry => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${enquiry.id}</td>
                <td>${enquiry.name}</td>
                <td>${enquiry.phone}</td>
                <td>${enquiry.service}</td>
                <td><span class="status-${enquiry.status.toLowerCase()}">${enquiry.status}</span></td>
                <td>
                    <button class="view-button" onclick="viewEnquiry(${enquiry.id})">VIEW</button>
                    <button class="delete-button" onclick="deleteEnquiry(${enquiry.id})">DELETE</button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    window.viewEnquiry = function(id) {
        alert(`View enquiry ${id}`);
        //  Implement view functionality (e.g., show details in a modal)
    };

    window.deleteEnquiry = function(id) {
        alert(`Delete enquiry ${id}`);
        //  Implement delete functionality (e.g., confirm and send a delete request)
    };
});