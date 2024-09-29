@include('pages.header')

{{-- hero section --}}
<div class="mt-5">
    <h5 class="text-center text-2xl font-bold mb-6 ">Help & Comment</h5>
    <div class="flex flex-col md:gap-6 px-4">
        <div class="w-full">
            <textarea name="message" id="message" cols="30" rows="15" class="w-full border border-gray-300 rounded-md p-2 mb-4 resize-none" class="resize-none"></textarea>
        </div>
        <div class="flex flex-row md:gap-6 w-full">
            <div>
                <input type="text" name="username" id="username" class="border border-gray-300 rounded-md p-2 mb-4 w-full" placeholder="userId">
            </div>
            <div class="w-full">
                <input type="text" name="comment" id="comment" class="border border-gray-300 rounded-md p-2 mb-4 w-full resize-none" placeholder="Comment">
            </div>
        </div>
    </div>

    <div class="w-full flex justify-center  px-4 mb-4">
        <button onclick="handleComment()" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Comment</button>
    </div>
</div>

@include('pages.footer')

<script>
    // Function to handle comment submission
    function handleComment() {
        const comment = document.getElementById('comment').value;
        const userId = document.getElementById('username').value;

        if (comment && userId) {
            localStorage.setItem('userId', userId);

            const formData = new FormData();
            formData.append('comment', comment);
            formData.append('user_id', userId);

            // Send the data to the backend
            fetch('/save-comment', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // alert('Comment saved successfully!');
                    // Fetch and update comments after successful submission
                    fetchComments();
                } else {
                    alert('Failed to save comment');
                }
            })
            .catch(error => console.error('Error:', error));
        } else {
            alert('Please fill in both the comment and user ID fields.');
        }
    }

    // Function to fetch and display comments
    function fetchComments() {
        fetch('/get-comments', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => response.json())
        .then(data => {
            const messageTextarea = document.getElementById('message');
            messageTextarea.value = '';  // Clear previous content
            data.comments.forEach(comment => {
                messageTextarea.value += `${comment.user_id}: ${comment.comment}\n\n`;
            });
        })
        .catch(error => console.error('Error fetching comments:', error));
    }

    // On page load, check local storage and fetch comments
    window.onload = function() {
        const storedUserId = localStorage.getItem('userId');
        if (storedUserId) {
            document.getElementById('username').value = storedUserId;
        }
        fetchComments();
    }
</script>
