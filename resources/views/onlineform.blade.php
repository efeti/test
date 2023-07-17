<!DOCTYPE html>
<html>
<head>
    <title>User Form</title>
</head>
<body>
    <form action="/onlineform" method="POST">
        @csrf <!-- Laravel CSRF protection -->

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <button type="submit">Submit</button>
    </form>
</body>
</html>





