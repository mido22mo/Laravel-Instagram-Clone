let chooingImg = document.getElementById("output");
let filterInput = document.getElementById("filterresult"); 

var ChangeFilter = function(value) {
    if (value == 1) {
        chooingImg.className = 'filter1';
        filterInput.value = 'filter1';
    } else if (value == 2) {
        chooingImg.className = 'filter2';
        filterInput.value = 'filter2';
    } else if (value == 3) {
        chooingImg.className = 'filter3';
        filterInput.value = 'filter3';
    } else if (value == 4) {
        chooingImg.className = 'filter4';
        filterInput.value = 'filter4';
    } else if (value == 5) {
        chooingImg.className = 'filter5';
        filterInput.value = 'filter5';
    } else {
        chooingImg.className = 'original';
        filterInput.value = 'original';
    }
};

var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) 
    }
   };


   function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const isVisible = sidebar.style.left === "0px"; 

    if (isVisible) {
        sidebar.style.left = "-200px";
    } else {
        sidebar.style.left = "0";
    }
}





document.addEventListener('DOMContentLoaded', function () {
    const followButton = document.getElementById('follow-button');

    if (followButton) {
        const userId = followButton.getAttribute('data-id');

        fetch(`/is-following/${userId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.isFollowing) {
                followButton.textContent = "Following";
                followButton.classList.remove("btn-primary");
                followButton.classList.add("btn-success");
                followButton.style.backgroundColor = "green";
                followButton.style.color = "white";
            } else {
                followButton.textContent = "Follow";
                followButton.classList.add("btn-primary");
                followButton.classList.remove("btn-success");
                followButton.style.backgroundColor = "";
                followButton.style.color = "";
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
        followButton.addEventListener('click', function () {
            fetch(`/follow/${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.action === 'followed') {
                        this.textContent = "Following";
                        this.classList.remove("btn-primary");
                        this.classList.add("btn-success");
                        this.style.backgroundColor = "green";
                        this.style.color = "white";
                    } else if (data.action === 'unfollowed') {
                        this.textContent = "Follow";
                        this.classList.add("btn-primary");
                        this.classList.remove("btn-success");
                        this.style.backgroundColor = "";
                        this.style.color = "";
                    }
                } else {
                    alert('Failed to toggle follow: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again later.');
            });
        });
    }
});


document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const authUserId = document.getElementById('authUserId').value;
    searchResults.style.width = searchInput.offsetWidth + 'px';

    searchInput.addEventListener('input', function () {
        const query = searchInput.value;

        if (query.length < 1) {
            searchResults.style.display = 'none';
            return;
        }

        fetch(`/search-users?query=${query}`)
            .then(response => response.json())
            .then(data => {
                searchResults.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(user => {
                        const li = document.createElement('li');
                        li.classList.add('list-group-item');
                        li.style.display = 'flex';
                        li.style.alignItems = 'center';

                        
                        const img = document.createElement('img');
                        img.src = user.image.startsWith('http') ? user.image : `/${user.image}`; 
                        img.alt = user.name;
                        img.style.width = '30px';
                        img.style.height = '30px';
                        img.style.borderRadius = '50%';
                        img.style.marginRight = '10px';

                        
                        const nameText = document.createTextNode(user.name);

                        
                        li.appendChild(img);
                        li.appendChild(nameText);
                        li.onclick = function () {
                            if (user.id == authUserId) {
                                window.location.href = `/home`;
                            } else {
                                window.location.href = `/user/${user.id}`;
                            }
                        };

                        searchResults.appendChild(li);
                    });
                    searchResults.style.display = 'block';
                } else {
                    searchResults.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error fetching search results:', error);
                searchResults.style.display = 'none';
            });
    });

    searchInput.addEventListener('focusout', function () {
        setTimeout(() => {
            searchResults.style.display = 'none';
        }, 250);
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const likeLinks = document.querySelectorAll('.like-link');

    likeLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault(); 
            const url = this.href;
            const postId = this.getAttribute('data-id');

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', 
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    const likeCountElement = document.getElementById(`like-count-${postId}`);
                    const likeIcon = this.querySelector('.like-icon');

                    if (likeIcon.classList.contains('fa-regular')) {
                        likeIcon.classList.remove('fa-regular');
                        likeIcon.classList.add('fa-solid', 'text-danger');
                    } else {
                        likeIcon.classList.remove('fa-solid', 'text-danger');
                        likeIcon.classList.add('fa-regular');
                    }

                    if (data.message === 'Like added') {
                        likeCountElement.textContent = parseInt(likeCountElement.textContent) + 1;
                    } else if (data.message === 'Like removed') {
                        likeCountElement.textContent = parseInt(likeCountElement.textContent) - 1;
                    }
                }
            })
            .catch(error => console.error('Error toggling like:', error));
        });
    });
});


Pusher.logToConsole = true;

    var pusher = new Pusher('2abd56423dd316d85639', {
      cluster: 'eu'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
      alert(JSON.stringify(data));
    });


    










