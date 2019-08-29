<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateQuickbookTokens1 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
			  DB::table('quickbook_token')->where('id', "3") 
            ->update(array(
            	        'client' => 'ABB6G2Da7EJLem4XfeXEn6vgdisRSVijSzdWNRKExRHVwssNVH',
                        'secret' => 'dUOLrdPi7J0hNzndtc7uUEconleZa9BeuoknEFay',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..2tYFC54CD1_z4CKZdzfXjQ.4eHycU5tfeo0168dfcz4wAMuw3rsyxqmrBt8y1Jt39epGfi4mJC2vM1hzupLJL4Rgc0oOlplvA9qm6NTohsPHEUsB9vQw5r72UU3s3d9ldHIPmRXAhFIy1PNDiylSLZ4xDSjD9sVaMQ7_s93g53gwbQkVYYtjLzTFrEAYcuRgyDhi7e-mrnxjykCGVPDsL_gzQlc4zdvNcev8g1Y8Z48DSmvxuQvc3q9-6Zl6iB6YVV3Pf5rGrFHsnvv1dPiQmktTaHy520P5UjKD_5YYTQ7aWzpW-TYPRW70pI6I1u24eEYP0_LdmK-UXMY7gzFc74QWOwvS3IjcvfjFWMp2GaAa2A65Y_BmfThyNOS0waA06QO9oZjPskTngGnZA5v_IhOkXtV5m9HytMcHT-3uSFUmrim-dc9n9StKzPB-JcIZ4e6OHcnusqxvRDlQhIl1LMMxINbX1KyZ5oZUiERTWW15FnTEfpRGvvc-xPS4-SybllIkgAzF0cgz1XWxR1muqWcrpXuuQHk6lgkrX7Ob0LQJ9CxlsH1OZvFCPUprnMqjKeKR_-92NdBtfmWPIytIBoHXFDjaedfD52CkaX6Zne0fsxtQKq2L3wGA2Q8VuToLgwdXolwnC3ww9MEQkKb3P2O0ahy0_3uIvmwfUW8BEf6EO0U_bSU0HmJ0RmpiT3BjbIB_RSwQyyNRFoe7WhNin-maGDO7ideXqR5w48sUJy2yHE8LzCFKFGC4x-ZcC9FG80.wulqfOj5hxy2uj1fykOe_A',
                        'refresh_token' => 'AB11575806591QvRGpgnZr85LvzE2iFlbh63wlqGFcLS9dWhUs'
                    )
                    );
		  DB::table('quickbook_token')->where('id', 4)
            ->update(array(
            	         'client' => 'ABpdVkDFhsmsp1KNFoDuYhgAATppzXoDlw9FFa7nE2PG9hmQZv',
                        'secret' => '3lnaubZB1MIo69RmH6geLezsPJM9aD99I8HsahXK',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..CLwNJ8cFtT3edP4yiwt1yQ.k-kDf4pCw9e6e1wdtl7KjG7X4KEDAheVhbuxaJ7to-JutE7I-fkJOAZ64Wv-hnYq50Gj_7skVuIt2FPmgDolEsn4fFWWrZVi6H9LVQCAJavfb0rqke-NysUsMwqjVA3qFp6eWk0Qmt7n7syMZknVd8JzlzD9kkmj2t1MmMsqlpP9q-LG0liDrHhmKO_ax60-bLljD7Dax4cqkuhuYNQuTWlf5pCNF4i3A8OWoTzMLHkyurJR37nmLLYljdOEbvzwOEkiNjo9QYqhGgf6T9k8qYOulIuT8jUsI2mr8Lbf_L5s1HkFWgk4WqsFZ6fZNtx9-3UZPZIAoGTU3U84hdBJ1IDifd7KBpghOkV_o4LXi8CHHnjFU7qjqExBZj9eIt7Qap23naAA98G72cstwiQA6ohZks4j80nKzQenonSTOpeTkGv9yuSwbIrplijXZBOq0taes_uVBTW8v4alGIw6lnN3IENNw-vdcqTjiS6QJCRuKc1wEMtDMJDOgI6awLRyB3Skw5SJm6UoXKu22wqv2zprHIFHDbvioUkxHXO3OhCzFGJ9HjVFV-ZiQO4oiTPJvHUu8DokZHS2IbxmF4v0xle8xStdrmorad21f66D5C9S-ubNDfv6FUnj-oOQfJHMtToyhcFJbfp2CZ2fHo0EKRze_8K0zG4NUM265cF-isBnQmnn_yx8M_-ky3ENyRq9jITIffDrtggi5zJNj6rbMy0kMSN2payoZzKxe5JTsHU.5p0eBC3TsqbdsXm8aDkpBg',
                        'refresh_token' => 'AB115758064036TrsBQSGQ74ymcX7hGnXsg1qTojTmC4G9M6nK'
                    )
                    );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		
	}

}
