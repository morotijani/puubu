		</main>

		<!-- JAVASCRIPT -->
		<!-- Vendor JS -->
		<script src="<?= PROOT; ?>assets/js/vendor.bundle.js"></script>
		
		<!-- Theme JS -->
		<script src="<?= PROOT; ?>assets/js/theme.bundle.js"></script>


        
	
	<!-- FOOTER -->
    <script type="text/javascript" src="<?= PROOT; ?>172.06.84.0/media/files/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="<?= PROOT; ?>172.06.84.0/media/files/popper-1.14.6.min.js"></script>
    <script type="text/javascript" src="<?= PROOT; ?>172.06.84.0/media/files/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= PROOT; ?>172.06.84.0/media/files/feather.min.js"></script>

    <script type="text/javascript">
      
            feather.replace();

      $(document).ready(function() {

        $.fn.extend({
          print: function() {
            var frameName = 'printIframe';
            var doc = window.frames[frameName];
            if (!doc) {
              $('<iframe>').hide().attr('name', frameName).appendTo(document.body);
              doc = window.frames[frameName];
            }
            doc.document.body.innerHTML = this.html();
            doc.window.print();
            return this;
          }
        });
        
        $("#temporary").fadeOut(3000);
        
        $('.select2getpositions').change(function() {
          if ($(this).val() != '') {
            var action = $(this).attr("id");
            var query = $(this).val();
            var result = '';
            if (action == 'sel_election') {
              result = 'election';
            }
            $.ajax ({
              url : "<?= PROOT; ?>172.06.84.0/controller/control.select2getpositions.contenstants.php",
                method : "POST",
                data : {action : action, query : query},
                success : function(data) {
                  $('#cont_position').html(data);
                }
            });
          }
        });

        $("#submitElectionSession").on('submit', function(event) {
          event.preventDefault();
          var ename = $('#election-session').val();
          var ctimer = $('#ctimer').val();

          if (ename == '' || ctimer == '') {
            alert('Select Both Election and Time Stopper');
          } else {
            $.ajax({
              url : '<?= PROOT; ?>172.06.84.0/controller/control.election.session.php',
              method : 'POST',
              data : $(this).serialize(),
              cache : false,
              success : function(data) {
                window.location = 'index';
              }
            });
          }
        });
      });
    </script>

    <script type="text/javascript">

        $("#displayErrors").fadeOut(6000);

        $(document).ready(function() {

            function load_data(page, query = '') {
                $.ajax({
                    url : "<?= PROOT; ?>172.06.84.0/controller/contol.search.all.php",
                    method : "POST",
                    data : {page:page, query : query},
                    success : function(data) {
                        $("#dynamic_content").html(data);
                    }
                });
            }

            load_data(1);
            $('#search_box').keyup(function() {
                var query = $('#search_box').val();
                load_data(1, query);
            });

            $(document).on('click', '.page-link-go', function() {
                var page = $(this).data('page_number');
                var query = $('#search_box').val();
                load_data(page, query);
          });

        });
            
    </script>
    
</body>
</html>
