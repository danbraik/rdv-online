		<p>
            Ce patient possède des rendez-vous.
            Si vous le supprimez, ses rendez-vous
            seront automatiquement annulés.
            <br/>
            Êtes-vous certain de vouloir le supprimer ?
        </p>
        <a data-role="button" 
			data-theme="a"
		   href="<?php echo $url_rm_yes; ?>
        " data-icon="delete"
        data-iconpos="left">
            Oui, supprimez le !
        </a>
        <a data-role="button" 
        href="<?php echo $url_rm_no; ?>">
            Non, conservez le.
        </a>
