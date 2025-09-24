<?php
// Array of students and their marks
$students = [
    ["name" => "Vignesh", "tamil" => 85, "english" => 78, "maths" => 92, "science" => 88, "social" => 80],
    ["name" => "Arun", "tamil" => 49, "english" => 60, "maths" => 60, "science" => 50, "social" => 56],
    ["name" => "Meena", "tamil" => 95, "english" => 88, "maths" => 90, "science" => 92, "social" => 85],
    ["name" => "Kiran", "tamil" => 30, "english" => 45, "maths" => 50, "science" => 35, "social" => 40],
    ["name" => "Ram", "tamil" => 65, "english" => 50, "maths" => 90, "science" => 67, "social" => 87],
    ["name" => "Ravi", "tamil" => 85, "english" => 78, "maths" => 10, "science" => 63, "social" => 55],
];

// Function to check if student passed all subjects (marks >= 50)
function getPassedStudents($students) {
    $passedStudents = [];

    for($s=0;$s<count($students);$s++){
        $student=$students[$s];
        if($student['tamil']>=50&&$student['english']>=50&&$student['maths']>=50&&$student['science']>=50&&$student['social']>=50){
            $passedStudents[]=$student;
        }
    }

    return $passedStudents;
}

//Failed Students only
function getFailedStudents($students){
    $failedStudents=[];

   $f=0;
   while($f<count($students)){
    $student=$students[$f];
    if($student['tamil']<50||$student['english']<50||$student['maths']<50||$student['science']<50||$student['social']<50){
        $failedStudents[]=$student;
    }
    $f++;
   }
    return $failedStudents;
}
// Function to get grade using switch statement
function getGrade($average) {
    switch(true) {
        case ($average >= 90):
            return "A+";
            break;
        case ($average >= 80):
            return "A";
            break;
        case ($average >= 70):
            return "B+";
            break;
        case ($average >= 60):
            return "B";
            break;
        case ($average >= 50):
            return "C";
            break;
        default:
            return "F";
            break;
    }
}


// Function to create and display student table
function createStudentTable($students, $title, $showGrade = false) {
    echo "<h2>$title</h2>";
    echo "<table border='2' cellpadding='8' cellspacing='3'>";
    echo "<tr>";
    echo "<th>Name</th>";
    echo "<th>Tamil</th>";
    echo "<th>English</th>";
    echo "<th>Maths</th>";
    echo "<th>Science</th>";
    echo "<th>Social</th>";

    if($showGrade) {
        echo "<th>Average</th>";
        echo "<th>Grade</th>";
    }

    echo "</tr>";
//For grade summary
foreach($students as $student) {
    $total = $student['tamil'] + $student['english'] + $student['maths'] + $student['science'] + $student['social'];
    $average = round($total / 5, 2);

    echo "<tr>";
    echo "<td>" . $student['name'] . "</td>";
    echo "<td>" . $student['tamil'] . "</td>";
    echo "<td>" . $student['english'] . "</td>";
    echo "<td>" . $student['maths'] . "</td>";
    echo "<td>" . $student['science'] . "</td>";
    echo "<td>" . $student['social'] . "</td>";

    if($showGrade) {
        echo "<td>" . $average . "</td>";
        echo "<td>" . getGrade($average) . "</td>";
    }

    echo "</tr>";
}
    echo "</table>";
}

//Get passed students
$passedStudents = getPassedStudents($students);
//Get failed students
$failedStudents = getFailedStudents($students);
// Display all students table
createStudentTable($students, "All Students Data");
// Display passed students table
createStudentTable($passedStudents, "Passed Students Report (All subjects >= 50)");
//Display Failed students table
createStudentTable($failedStudents, "Failed Students Report (Any subject < 50)");
// Display grade summary table
createStudentTable($students, "Student Grade Summary Report",true);
?>
