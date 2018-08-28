<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.css">
<div class="container">
    <div class="row">
        <h2>Créer un sondage</h2>
        <hr>
        <div class="box">
            <div class="box-body">
                <form class="form-horizontal" method="POST" data-ajax="true" action="<?= $this->Html->url(array('controller' => 'Sondage','plugin' => "Sondage", 'admin' => true, 'action' => 'ajax_create')); ?>" data-redirect-url="<?= $this->Html->url(array('controller' => 'Sondage','plugin' => "Sondage", 'admin' => true, 'action' => 'index')); ?>">
                    <label><i class="fa fa-question-circle"></i> Votre question</label>
                    <input type="text" name="question" class="form-control"><br />
                    <label><i class="fa fa-edit"></i> Vos réponse(s)</label>
                    <div class="input-group">
                        <input placeholder="Réponse #1" type="text" name="data_resp[1]" class="form-control">
                        <span class="input-group-btn">
                          <button class="btn btn-success btn-add-cmd" data-i="2" type="button"><i class="fa fa-plus"></i></button>
                        </span>
                    </div>
                    <div class="addCommand"></div><br />
                    <label><i class="fa fa-calendar"></i> Date d'expiration</label>
                    <input class="datepicker form-control" name="dateExpire" type="text" data-date-format="yyyy/mm/dd"><br />
                    <button type="submit" class="btn btn-success">Créer</button>
                </form>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.js"></script>
<script>
$(document).ready(function() {
  $('.btn-add-cmd').click(function() {

    var i = parseInt($(this).attr('data-i'));

    var input = '';
    input += '<div style="margin-top:5px;" class="input-group" id="'+i+'">';
      input += '<input name="data_resp['+i+']" placeholder="Réponse #'+i+'" class="form-control" type="text">';
      input += '<span class="input-group-btn">';
        input += '<button class="btn btn-danger delete-resp" data-id="'+i+'" type="button"><span class="fa fa-close"></span></button>';
      input += '</span>';
    input + '</div>';

    i++;

    $(this).attr('data-i', i);

    $('.addCommand').append(input);

    $('.delete-resp').unbind('click');
    $('.delete-resp').on('click', function(e) {
      var id = $(this).attr('data-id');
      $('#'+id).slideUp(150, function() {
        $('#'+id).remove();
      });
    });
  });
});
$('.datepicker').datepicker({
  format: 'dd-mm-yyyy',
  language: 'fr',
  startDate: '+1d'
});
</script>
