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
                        'client' => 'ABOXTr7RAmUXVmeE82mITkaYze5K6dQmKSLKCZEoksLswpq6CF',
                        'secret' => 'uSaSnRgqEoZS5mv9U06V83iUKJhlSke8vAXPyi6i',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..IjuL8K-CPxkhi0Mg63bh8A.k_qyHFv0IG2_o0fhyIUGrLxcGTpn4za1asyfQqwW0cHVagpp0MzLLDGoibfLjV0yIqBR3qxChABtCyuzPdZG7rojAXF3s13G13LmmwVo8480_PrRCVaTOtv_2M1NSN-jXekPLf5vO8VfQL7GotB2nwazwHRgcqjdhXCD9KS7_P54b2MZj6DiN0uIR5N-2B6Z_DtoLVEa_k_i9RI4dWJcQAqU_UMU6Kpfs6r8RujffBlVFEvF9FJpFpUPHdZXujeC31qxfvx1lX0M0_XuJoeIpmPAjEolemKgpUdUpk1VY_tRdZeO2fYNe3lXFiOZTWaDkkdvhDyVV7Y2IKNz8zL-cWiBkDjEsxNLeb4zgx4gN5bNfhbcbn9uEgU83fqQFzMRe2JhrQVOPDqM2dX2_5wlCYSWwpSYiBvdq3qwiMdSHPvx5ChFCY5Jd6Qnd-hpqEdYeODwgmNViikgUc2M3AgJ0uNyKh6LVlwea-C3GrTBwwg-D5q5csXQGMgvVKI3HHSs6sOo57MgSTP2VsIRXDHZk6yzDEC2EaV33FqiO0bWFE3d5J2lhSY4mvWgHy90Tl3Vsn1MHVuc5xD9JtymNdlnHNfA1tiw5OqA-g7gmNXiEmm6aYkqy9pCbVQkHSyDCgfj75Rc394gAylY4pgeJ2ssqKX9KjIHDHgO22AY2osrYMbeW_qKlR1f3tuOkhgyYjBEVajtHe4jHdRTVkW1nWmgIDGcFpJcfyCfNOe5zA5WU6A.AoczZWQaUS1FSsXZUjmuiA',
                        'refresh_token' => 'AB11604042806wGRKxjrfKZFVUOroyA5p7DSEPOKrvSYYnbEJ8'
                    ),
                    //Plus GST account
                    array(
                        'id' => '2',
                        'client' => 'ABKxbBba5bH6RNqtT6d83ZW4wFtrtPVJq1XgB8T0qZ09RHdC1L',
                        'secret' => 'bygKYjkZhpGLm4JiStyNFKfC6ahlzBKKtvXDjSs8',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..GCE-S_eDnSQPM_h0jo4DlA.Um-mit3AeymzmuJAI0VWnGaefxN7TZNkehxwdnAV7hkeE8nJ8EOvn9WKGLv-mF99mZFqZMVBT4GBf3q_dZGLq9hVH3Xm3N0a6vocWd7LziVUzFLNrw54975J8X0qtiKz_ujngOnbcCU6LUJqXAIKtDpm9GHbSBfLIVQHa2cd_imWcMDg1fgau6yIyFKjrnph0YIDlL3RfUHjdB7knzAzKlPfjoYS0e7CBUqh8RQRVqglwuLMjFryCrGPT0zUDB0YkH2PTPdfn0pgtsAd0PNHvWNHHtW8GfoJvwyTtiQp2WMNfAH71NP87yIQknTQCawBWJrfyzODDWki3KUziERuymYfglzERH-_y2LGh3Oa6OVMur_8kG1fpOMtEdanUOywruETmJfEkRQO3WtvL_EAbmWbDFPbcBNJBW5JZweOi44r1J4YTP9XOglh2RkuWUlcZRFU7XnnQsQNtfoX1_kL7TBQ6w0DRFFZr9znqVwsfSbfDP2D6pHI-ii2G0T7xalq-PbTTr7jomNdOGN3B0LpxBY_QqNkNAJjj7aIr0E7Vz84WLw2VEQ1ALQhF-ym1I7x_ocs034QiLyRrupgtL7bEypU7kvmMAPJkpeAroOpUpZZsKkLO7ZqQNcjvmBZWgJTTmwbp1n9wfae1nvovHp69jCbaO317XhKZ11Kj0vaPpVEd9tb9KJ9sXwOL7ZgUVCn4XSMivAga4ioo4OQ4Fo8L00YAY3VHyp7IPEtKLZNVSg.CtXXRfPxW6SbI1C3eXQfJA',
                        'refresh_token' => 'AB11604041705aaNrOi6yl7ASmNB8iklQwoj77jSC1SF5iTFt1'
                    )
        ));
        echo 'success';
    }
}
