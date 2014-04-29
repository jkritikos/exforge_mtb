<!-- Sidebar -->
<aside class="grid_2">		
    <div class="widget">				    
        <header>				    
            <h2>Options</h2>			    
        </header>

        <section>				    
            <dl>		
                <dd><img src="/img/fam/usage.png" />&nbsp;<a href="/reports/dashboard">Dashboard</a></dd>
                <dd><img src="/img/fam/chart_curve.png" />&nbsp;<a href="/reports/activeUsers">Ενεργοί Χρήστες</a></dd>
                <dd><img src="/img/fam/chart_line.png" />&nbsp;<a href="/reports/scoresDistribution">Κατανομή πόντων</a></dd>
                <dd><img src="/img/fam/chart_bar.png" />&nbsp;<a href="/reports/gamesBreakdown">Ανάλυση παρτίδων</a></dd>		    				                                    				    
            </dl>				    
        </section>				    
    </div>	
    <div class="widget">                    
        <header>                    
            <h2>Reported Questions</h2>                
        </header>

        <section>                   
            <dl>     
            	<?php if($countReportsGreek > 0 ) { ?>
            		<dd><img src="/img/fam/gr.png" />&nbsp;<a href="/reports/questionReport/<?php echo LANG_GREEK ?>">Ελληνικές Ερωτήσεις   (<?php echo $countReportsGreek  ?>) </a></dd> 
            	<?php }else { ?>
            		<dd><img src="/img/fam/gr.png" /> Ελληνικές Ερωτήσεις (<?php echo $countReportsGreek  ?>)</dd>
            	<?php }   ?>                       
            </dl>                                     
        </section>                  
    </div>  
    <div class="widget">				    
        <header>				    
            <h2>Feedback</h2>			    
        </header>
        <section>				    
            <dl>
            	<?php if($countFeedbacks > 0 ) { ?>		
                <dd><a href="/reports/feedbackList">View List (<?php echo $countFeedbacks  ?>)</a></dd>	
                <?php }else { ?>    
                <dd>View List (<?php echo $countFeedbacks  ?>)</dd>
                <?php }   ?>				                                    				    
            </dl>				    
        </section>				    
    </div>	
    	
</aside>
<!-- Sidebar End -->