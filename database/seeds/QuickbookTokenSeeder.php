<?php

use Illuminate\Database\Seeder;

class QuickbookTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    public function run()
    {
        DB::table('quickbook_token')->truncate();
        DB::table('quickbook_token')->insert(
                array(
                    //All inclusive account
                    array(
                        'id' => '1',
                        'client' => 'ABWk4dMc4UcBuLlGJe3LJxVlocXWcDqnwJsfm1LB5ryoYo4etk',
                        'secret' => 'uCQMa6FiYwVw7vxxCuvvWBprKHoBR9NJC3fZKWV8',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..A6jgrUxIeIA3eLnKxNgYyA.67oQsRUUbiDLtvI5CpZy1h_MhJWXJD4Pj98IqBtfGceoY6oYeHoNNRASvjHUFkf9DvHJFZWnpXQSDadOxJLUFyUhgZO_uCLYHBzjJo82tOEFjdG5cMKBpSvq0IO2EvIH98YGittfVEHd9XNnJvhhAi3yEob2Ut2tUte_GVvWoWORjue08a8u_KfLn3LMMn65_6arI_Szt0wRPQJ1RG0HGaxnlUgpZ3RfpMQZQOMzGKD_jfudHNfNAmVsXru1rfkJQLigsy_tBNqz1CCRMBuTZTUj-LLMRP8VdywZu6h6A1nOdIhP6vDRagUfS6QUCM1dWSZ8HkDlKAKhf2_pSYDOlPB5GaljQ8PFLyC-OWI5gplreIQmOTzXFssoIEa-3-yy55NeOrRST3Ky7jDRuntDVXla8Xynuj9S4u3wpGlm2AZxa9r97zgxri6N-nTGx6UQvG_JFeA42ofQhx5b85lGJYlC8aECg3ZPQs0iEAFvkI2HDRotcfeSalfVsPxg9bL9gL8XOhdgDN--lcOh25C8FwK4SE8VubNDUulxSo9V51QJQeuUCzT_e2a3E5FcetDpULtsb0xdjPagJU7hPNn-quSKvbdl_MyzD91_yqRMN4ol0EEqC92mbOG_Iu-dJtv-LvpVI_sakq0JaF3fqg5VfUT9lCdCwyNO_A7nR5bR881Fy9i8dz0yJb4Efi7GrqdHTodXBJ9iTRRHk9jKGx6JuK4VRb3_k1yFz75Tq_xLVwk.PVORuK0cLA8ap0m1hNyYdg',
                        'refresh_token' => 'AB11604072057zsHNR8yrJHViGVTQyKcOtadqWsGhdxiWt7WrF'
                    ),
                    //Plus GST account
                    array(
                        'id' => '2',
                        'client' => 'ABUzIaOgsZ54sjvRekkFGfXmJ0Rj7fPwjWPVsKp9gqb6HgvekI',
                        'secret' => 'vHiQpf7DnTsqDEnFf0IIGqVVR76PF2zDPMMWGa9x',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..PW89BRoL2XIg8RJNp4-bRw.TGZQtz3oLTvnjFF9zRG-mNony8SQHzBlaqdisNKm9C7WD_e0cpbbYg8yQppr32guarmpqCqzfg2mT3oal1QA720-41jdqiJny2Iim4KpD-7F2EyId4gg3M5OCAASUs3iHU-He9lgQ1w5OMpKK9jgsw9unY8u8dwJUfFqi3A3LGujDvtTeRxQp3yx7WofrGp8EM4tnP3zzk7p-sCdu_A5UBvh9G4Ul13_UcQXCe8FHLeRYdIfGQVGlRqvStmPNut9583UcMe0BN_j92PiYeWEgKQxLsSHnpDxfX2D_ppqvs43sVJ1qq3p8-Jg49l9ArE0sElQlftQTMoREzYddJzqeXZHqJFzRACBEWP0Qfd1duSVxMeFFYuA7GSGssYk-ksd4muE85zEWd03Fm2qZy0DcUEOxHjk9i1K4ai2E3q8BIZL_pjuVyzFIDLZJvRFh98N-KDL1Ou2ZXcWNI1huURdqURUFY-Av6j3p86QQKES-WM85a1WQuNbqGTTOMi1ux0Igpc1ZRRc2hpu3FkZuUnfq-0BQC6XABiYu_cbty2hI5F6HmyKOgbD4LgLFvU_nkl6Fr1BwoLkr9_r9VvDgfw_lZtSs6yvt2b_vMKOe8FXhNw_5quGOEu_nZTipDBwFLrqno2u1RN8QrS7pO1TlcqTXMaa5wv8u4ElvxulqsN-eWzJ1P5-AXHJ4svXiVD1L8y5I4Hz11LnQnj4fzlwUXVU-SItE5o1x8c5u3YrDD7ggNI.upPazhd1UqgNC-g3jVZEAQ',
                        'refresh_token' => 'AB11604071721LF1I2sQh1NJr6z2ND1qLVJeGEEKZRk5kZFJWt'
                    )
        ));
        echo 'success';
    }
}
