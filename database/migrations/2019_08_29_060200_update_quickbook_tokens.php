<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateQuickbookTokens extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
			  DB::table('quickbook_token')->where('id', "3") 
            ->update(array(
            	        'client' => 'ABpdVkDFhsmsp1KNFoDuYhgAATppzXoDlw9FFa7nE2PG9hmQZv',
                        'secret' => '3lnaubZB1MIo69RmH6geLezsPJM9aD99I8HsahXK',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..Z3PZlF_mvm0VNJsVYKMJjg.oli7Z7l5pB21xclb_f-Br63LwcKmVo5zjJ43FCS3TbHScmcfuHir0RgwpXvocur6A3Ykjl27gsd_RLyCSnWmvd4uw-jDLEoZOabgFv-Jo70pYv9Nv0nZzyMah_G7QyLps89RPO2-U6FeXvbH-zhKMDwC6LWfULZlxtfb4i4U1XciJOlEID1Eqve_6quY0cn8qhQ0l7oP0kChIFpyJB9kr6tWepC8rORBKoQA3Uu8NdBqEMMueNfajPMlUJkC8siBarRje3rNryxk7rXTn900ZLVlVTI9BKDVajNv7Zg4TecuTUssat0kF-NZ7dPgJT_JbhQyoWBw3A_r3e_iLLvKcMV4S4GwMCvMUFNEeNYMbexZQYcNBm6OzlH7ERZ4Sl9IsBfHP609ws0PL6CeySmflE4CIgoBq1XjKkobp4h1dQFOM7RU8s31yuoeUrFvLGdHjpUNDegaluMpDLL73l4-_bbZrUDf4ZP5vnKZNgJOM08eqpB2oOPqmbElEnXqpFypZtF9xAzmIrKCg2kqbH92SplA-qrR299dzrE6cq3Ck-YpW_L54Wiqg7tGq1M_3Tg07te8ZsPNjd6G4C_JGVGsc6BxgiPMItZ8MtvQ12hiPG6vbb0q59milZSBN0Du4Jm33FP7EsM1FOkByHH6DTQ-1bUlsT4ML192rllcHBXCYsL5maxHoN0euc4_8GURSD20mjYB3FP0vzet6D_pu_-pXyB4FMbwYwJSX8PXCQd-6kI.2fG7H7ZNX3MDPi3zzw-8Qg',
                        'refresh_token' => 'AB11575808490GKTR8AbwxNpSKuGbdgwu84KEqlfluNzgbm5YN'
                    )
                    );
		  /*DB::table('quickbook_token')->where('id', 4)
            ->update(array(
            	         'client' => 'ABpdVkDFhsmsp1KNFoDuYhgAATppzXoDlw9FFa7nE2PG9hmQZv',
                        'secret' => '3lnaubZB1MIo69RmH6geLezsPJM9aD99I8HsahXK',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..CLwNJ8cFtT3edP4yiwt1yQ.k-kDf4pCw9e6e1wdtl7KjG7X4KEDAheVhbuxaJ7to-JutE7I-fkJOAZ64Wv-hnYq50Gj_7skVuIt2FPmgDolEsn4fFWWrZVi6H9LVQCAJavfb0rqke-NysUsMwqjVA3qFp6eWk0Qmt7n7syMZknVd8JzlzD9kkmj2t1MmMsqlpP9q-LG0liDrHhmKO_ax60-bLljD7Dax4cqkuhuYNQuTWlf5pCNF4i3A8OWoTzMLHkyurJR37nmLLYljdOEbvzwOEkiNjo9QYqhGgf6T9k8qYOulIuT8jUsI2mr8Lbf_L5s1HkFWgk4WqsFZ6fZNtx9-3UZPZIAoGTU3U84hdBJ1IDifd7KBpghOkV_o4LXi8CHHnjFU7qjqExBZj9eIt7Qap23naAA98G72cstwiQA6ohZks4j80nKzQenonSTOpeTkGv9yuSwbIrplijXZBOq0taes_uVBTW8v4alGIw6lnN3IENNw-vdcqTjiS6QJCRuKc1wEMtDMJDOgI6awLRyB3Skw5SJm6UoXKu22wqv2zprHIFHDbvioUkxHXO3OhCzFGJ9HjVFV-ZiQO4oiTPJvHUu8DokZHS2IbxmF4v0xle8xStdrmorad21f66D5C9S-ubNDfv6FUnj-oOQfJHMtToyhcFJbfp2CZ2fHo0EKRze_8K0zG4NUM265cF-isBnQmnn_yx8M_-ky3ENyRq9jITIffDrtggi5zJNj6rbMy0kMSN2payoZzKxe5JTsHU.5p0eBC3TsqbdsXm8aDkpBg',
                        'refresh_token' => 'AB115758064036TrsBQSGQ74ymcX7hGnXsg1qTojTmC4G9M6nK'
                    )
                    );*/
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
