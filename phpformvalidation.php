<?php
// Initialize variables
$name = $dob = $phone = $email = $website = $skills = $gender = $otherGender = $password = $confirmPassword = "";
$fileError = "";
$errors = [];
    $allowedSkills = ['PHP','Java','Python','JavaScript','C++','HTML','CSS'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // ===== Name Validation =====
    $name = trim($_POST['name']);
    if (empty($name)) {
        $errors['name'] = "Name is required";
    } elseif (strlen($name) < 2 || strlen($name) > 30) {
        $errors['name'] = "Name must be between 2 and 30 characters";
    } 

    // ===== Date of Birth Validation =====
    $dob = $_POST['dob'];
    if (empty($dob)) {
        $errors['dob'] = "Date of birth is required";
    } else {
        $age = (int) ((time() - strtotime($dob)) / (365*24*60*60));
        if ($age < 18) {
            $errors['dob'] = "You must be at least 18 years old";
        }
    }

    // ===== Phone Number Validation =====
    $phone = $_POST['phone'];
    if (!preg_match('/^\d{10}$/', $phone)) {
        $errors['phone'] = "Phone number must be 10 digits";
    }

    // ===== Email Validation =====
    $email = $_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    // ===== Website Validation =====
    $website = $_POST['website'];
    if (!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) {
        $errors['website'] = "Invalid website URL";
    }

    // ===== Skills Validation =====
    $skills = $_POST['skills'];
    if (!in_array($skills, $allowedSkills)) {
        $errors['skills'] = "Select a valid skill from the list";
    }

    // ===== File Upload Validation =====
    if (isset($_FILES['resume'])) {
        $file = $_FILES['resume'];
        $fileSizeMB = $file['size'] / (1024*1024);
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($fileExt != 'pdf') {
            $errors['file'] = "Resume must be a PDF file";
        } elseif ($fileSizeMB > 5) {
            $errors['file'] = "Resume size is below 5MB";
        }
    } else {
        $errors['file'] = "Resume file is required";
    }

    // ===== Gender Validation =====
    if (empty($_POST['gender'])) {
        $errors['gender'] = "Select a gender";
    } else {
        $gender = $_POST['gender'];
        if ($gender == 'other') {
            $otherGender = trim($_POST['otherGender']);
            if (empty($otherGender)) {
                $errors['otherGender'] = "Please specify other gender";
            }
        }
    }

    // ===== Password Validation =====
    $password = $_POST['password'];
    if (strlen($password) < 5 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[0-9]/', $password) ||
        !preg_match('/[\W]/', $password)) {
        $errors['password'] = "Password must be at least 5 chars, include 1 uppercase, 1 number, 1 special char";
    }

    // ===== Confirm Password =====
    $confirmPassword = $_POST['confirmPassword'];
    if ($password !== $confirmPassword) {
        $errors['confirmPassword'] = "Passwords do not match";
    }

    // ===== Submit Only if No Errors =====
    if (empty($errors)) {
        echo "<div>";
        echo "<h3> Form Submitted Successfully!</h3>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr>";
        echo "<th>Name</th>";
        echo "<th>Age</th>";
        echo "<th>Phone</th>";
        echo "<th>Email</th>";
        echo "<th>Website</th>";
        echo "<th>Skills</th>";
        echo "<th>Gender</th>";
        echo "<th>Resume</th>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>" . htmlspecialchars($name) . "</td>";
        echo "<td>" . htmlspecialchars($age) . " years</td>";
        echo "<td>" . htmlspecialchars($phone) . "</td>";
        echo "<td>" . htmlspecialchars($email) . "</td>";
        echo "<td>" . htmlspecialchars($website ?: 'Not provided') . "</td>";
        echo "<td>" . htmlspecialchars($skills) . "</td>";
        echo "<td>" . htmlspecialchars($gender == 'other' ? $otherGender : $gender) . "</td>";
        echo "<td>File uploaded</td>";
        echo "</tr>";
        echo "</table>";
        echo "</div>";
        echo "<br>";

        // Clear form data after successful submission
        $name = $dob = $phone = $email = $website = $skills = $gender = $otherGender = $password = $confirmPassword = "";
        $errors = [];
    }
}
?>

<!-- HTML Form -->
<form method="post" enctype="multipart/form-data">
    Name: <input type="text" name="name" value="<?=htmlspecialchars($name)?>"> <?= $errors['name'] ?? "" ?><br><br>
    Date of Birth: <input type="date" name="dob" value="<?=htmlspecialchars($dob)?>"> <?= $errors['dob'] ?? "" ?><br><br>
    Phone: <input type="text" name="phone" value="<?=htmlspecialchars($phone)?>"> <?= $errors['phone'] ?? "" ?><br><br>
    Email: <input type="email" name="email" value="<?=htmlspecialchars($email)?>"> <?= $errors['email'] ?? "" ?><br><br>
    Website: <input type="url" name="website" value="<?=htmlspecialchars($website)?>"> <?= $errors['website'] ?? "" ?><br><br>
    Skills: 
    <select name="skills">
        <option value="">Select Skill</option>
        <?php foreach ($allowedSkills as $s) {
            $selected = ($skills==$s) ? "selected" : "";
            echo "<option value='$s' $selected>$s</option>";
        } ?>
    </select> <?= $errors['skills'] ?? "" ?><br><br>
    Resume (PDF, MAX 5MB): <input type="file" name="resume"> <?= $errors['file'] ?? "" ?><br><br>
    Gender: 
    <input type="radio" name="gender" value="male" <?=($gender=='male')?'checked':''?> onclick="handleGenderChange('male')"> Male
    <input type="radio" name="gender" value="female" <?=($gender=='female')?'checked':''?> onclick="handleGenderChange('female')"> Female
    <input type="radio" name="gender" value="other" <?=($gender=='other')?'checked':''?> onclick="handleGenderChange('other')"> Other
    <input type="text" name="otherGender" id="otherGender" placeholder="Specify" value="<?=htmlspecialchars($otherGender)?>" 
           <?=($gender != 'other') ? 'disabled' : ''?>> 
    <?= $errors['gender'] ?? $errors['otherGender'] ?? "" ?><br><br>
    Password: <input type="password" name="password"> <?= $errors['password'] ?? "" ?><br><br>
    Confirm Password: <input type="password" name="confirmPassword"> <?= $errors['confirmPassword'] ?? "" ?><br><br>
    <input type="submit" value="Submit">
</form>

<script>
    function handleGenderChange(gender) {
        if (gender === 'male' || gender === 'female') {
            document.getElementById('otherGender').disabled = true;
        } else {
            document.getElementById('otherGender').disabled = false;
        }
    }
</script>
