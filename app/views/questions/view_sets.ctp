<section id="content">
    <div class="wrapper">

    <!-- Left column/section -->
    <section class="grid_6 first">

    <div class="columns leading">
        <div class="grid_6 first">

            <h3>Question sets:</h3>
            <hr />

            <table id="projectTable" class="paginate sortable full">
                <thead>
                        <tr>                            
                            <th align="left">Name</th>
                            <th align="left">Language</th>
                            <th align="left">Active</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php

                    foreach($sets as $key => $val){
                        $setId = $val['QuestionSet']['id'];
                        $viewLink = "/questions/viewSet/$setId";
                        $name = $val['QuestionSet']['name'];
                        $language = $val['Language']['name'];
                        $status = $val['QuestionSet']['active'] == 1 ? "Yes" : "No";
                        ?>
                        <tr>                            
                            <td><a href="<?php echo $viewLink; ?>"><?php echo $name; ?></a></td>
                            <td><?php echo $language; ?></td>
                            <td><?php echo $status; ?></td>
                        </tr>
                            <?php
                    }

                ?>

                </tbody>
           </table>
	 </div>
    </div>

    <div class="clear">&nbsp;</div>
</section>
<!-- End of Left column/section -->
<!-- Right column/section -->

<!-- Right column/section -->	
<?php echo $this->element('menu_question'); ?>		
 <!-- End of Right column/section -->

</div>
</section>