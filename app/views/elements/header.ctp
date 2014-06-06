<?php
$name = $session->read('fname') . " " . $session->read('lname');
$role = $session->read('role');
$contentLanguage = $session->read('content_language');

//active tab
$classQuestions = "";
$classUsers = "";
$classReports = "";
$classScores = "";

$this->log("header.ctp active tab is $activeTab role is $role", LOG_DEBUG);

if($activeTab == 'questions'){
    $classQuestions = "class=\"active\"";
    $classUsers = "";
    $classReports = "";
    $classScores = "";
} else if($activeTab == 'users'){
    $classQuestions = "";
    $classUsers = "class=\"active\"";
    $classReports = "";
    $classScores = "";
} else if($activeTab == 'reports'){
    $classQuestions = "";
    $classUsers = "";
    $classReports = "class=\"active\"";
    $classScores = "";
} else if($activeTab == "scores"){
    $classQuestions = "";
    $classUsers = "";
    $classScores = "class=\"active\"";
    $classReports = "";
}

?>
<header id="page-header">
    <div class="wrapper">
        <div id="util-nav">
            <ul>
                <li>Έχετε συνδεθεί ως: <?php echo $name; ?></li>
		<li><a href="/users/profile">Προφίλ</a></li>
                <li><a href="/users/logout">Έξοδος</a></li>
            </ul>
        </div>

        <img height="36" src="/img/logo.png"/>
        <h1>X Challenge</h1>

        <div id="main-nav">
            <ul class="clearfix">
                <li <?php echo $classScores; ?> ><a href="/scores/">Βαθμολογίες</a></li>
                <li <?php echo $classQuestions; ?> ><a href="/questions/create">Ερωτήσεις</a></li>
		
                
                <?php
                if($role == '2'){
                    ?>
                    <li <?php echo $classUsers; ?> ><a href="/users">Χρήστες</a></li>
                    <li <?php echo $classReports; ?> ><a href="/reports/dashboard">Reports</a></li>
                    <?php
                }
                ?>
                
                <!--
                <li id="quick-links">
                    <?php if ($contentLanguage == 1){ ?>
                        <a href="#">Ελληνικό Περιεχόμενο<span>&darr;</span></a>
                    <?php }else if ($contentLanguage == 2){ ?>
                        <a href="#">Αγγλικό Περιεχόμενο<span>&darr;</span></a>
                    <?php } ?>
                    <ul>
                        <li><a class="flag" id="1" href="#">Ελληνικό Περιεχόμενο</a></li>
                        <li><a class="flag" id="2" href="#">Αγγλικό Περιεχόμενο</a></li>
                    </ul>
                </li>-->   
                    
            </ul>
        </div>
    </div>
    <div id="page-subheader">
        <div class="wrapper">
            <h2><?php echo $headerTitle; ?></h2>
                <input id="tagSearch" placeholder="Αναζήτηση tags..." type="text" name="q" value="" />
        </div>
    </div>
</header>