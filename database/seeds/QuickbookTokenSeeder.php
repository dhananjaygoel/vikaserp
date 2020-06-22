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
                        'client' => 'ABfN2ld2b7J2FTN22uTqLPCFkx9f99Sj4YvDsMmVHD3mNUyuWU',
                        'secret' => '9peqxCWOzdpp1cEKPnAsPAAyEz77Z930q2QADnoe',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..L0tcl2OFXlGObwq2JyTIzg.RO4vFe_YUtTInelGpvosNxrI8h9RLhCLJDFfin_Cpmg-F4KV1fzpKb_ohGbhL_bp04NATw35y4t1kxj9FyNGfd2C-61zkbpMAjP2t1VLJs0q6eQRdEX0R2VFb3Cas0OvZM8ZT38eSISPzyugWlT2IgkfO4RNv-W9fMVuwRqAYvThg7TBxLssKu6IelIGdtUC-wRZbonyZmnHcArRXcua5OutLMUmDOHbbZW5D-zXQ_3nghDldOjFVxI7YGhNgjdRoz0DlWuae7VpQdXw6F-oC5Q6L_vOep6aF0h5dz1ufF8Kb_J1i8dH-YaQK9bfpPdqu3OaHtk5c7nkNdPdtMYQh4vifRQngwy9uRXdgMyPQqMjr3IkVqfD9cph7ixJ005Za2heseYOiwBbh8RnQ39zg2gyfJUZZolIiiLa88e15RpDXFNF_LPXoocJb67pQuccxIF-lQL6cbxzZUGXrAsxNWVlFsie5dZhBJWsvgOnZCY2HxeBeWOtMULh0YwbJxPpB6pDwaMaRLurLKQGtq498Y1jQ_K2S0UNMAfcmyRniuTHBgd7TXvTzk38U8jseIGMzlRbhE91wk_pf_HheorczVzB4tdg9MCLaTOXf2F_QfSQKoaZHdn7UTSehcs_d-ypp9bZl4plVRV8dEZ0h8iQks0ZO4NVGcBBgpbSRx-Smlf9HCmW_ZuDxJidn5pbbUkPC0U2fWKvSTVyUQwu_QdpGp61-5ChZvBwi5jZhVm_mGQ.NfJDwOv_yJeEXenC1G2d1A',
                        'refresh_token' => 'AB11601537141XRI5NBJiWnODezChMqb64520VBuRsx90vNhsu'
                    ),
                    //Plus GST account
                    array(
                        'id' => '2',
                        'client' => 'ABSSab3mSZlIGJLQ1tCZSKKpd7LN7OI3zacIfFW0hLsHLVMnyp',
                        'secret' => 'PQLjc67R23HENkuvNmgj2YY3QVn1OcBHXNtIJvpP',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..3rnE-NVVWtYOOhQ81LS2OA.TgKyVNgHA2KLWhcpZjAFnG90NCwANdLZU4G5B2atczV_BLDyoHHmHGTF23nz4sHWlSXRkd6Bcwkkur4IRmMGKZI8iVRpvNT75LWdedKmX7IuzeAzCYs0yLTmOE1Vj988PYCH-3ZO1meefQH4CXZh6KCB3i3JRutxG2H3OM3PLVlJnO9TFUO_8o07pe9GlUZWLpl0Q7FCatsq-C9eCzc4CEIcAPPEmQOj7-r8GI5-uR22Wyc1h9TqJrNaWViSLbVRLWBhTJU7ixKu27Zbd2octgpcEonaoJlcxKBfr5aSaaegSbMa9rt6ojaDuVH3EZT80JBv0hB4_Z75Nh-h3PSnLlFRFn_2ctGc6tenYfOjav1obJVNE8idrLCdLY38oWN3GpCj1BqjFZE84-ZHjS4_mqFIAV7Ij6CV2-U9I1PrmW4SIznSJEn7k99eS3X1M71HLT1QHAfo5mIMgo5ZqlyEk84UKKgID5IMpeJ_qxig9TT-WE9A8VeubU6BIv4sm4XGi-upvgqGwPUKbAbhst0f0ptRiTjBISujxCpdHx0CWH4NXDv36_pQBKT_1k9r-kcCD_x4JBghX2lfkSDb4It--rJfxWvBKY_tlTWacMOsN4yuHezVqyPrEzQPoB4A2J7tCKMZ3mZVO2HF5_rJJLMi-Sx4TjJ5BamEJV2jTXIViRU_XmTvWlKD4kfEYfAvFnsKjmuQnuQ3EMB9JxZ7Tlrd4sXFKikT6_rhI2b3JRi7p7o.asHP35USnykplkj3Ersdag',
                        'refresh_token' => 'AB11601537033OHJJLIMDbUCefklQ880Ioi4OR9ZMA3ThBQEh6'
                    )
        ));
        echo 'success';
    }
}
