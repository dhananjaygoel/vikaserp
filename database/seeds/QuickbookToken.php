<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuickbookToken extends Seeder {

    public function run() {
        DB::table('quickbook_token')->insert(
                array(
                    //All inclusive account
                    // array(
                    //     'id' => '2',
                    //     'client' => 'Q0RdWcaasGzlKnDFpr39BHICZX2ve0n4cfSUJJeB0XsoDP5C6X',
                    //     'secret' => 'c66GKMcvFSUc0hgtQBqIhkZnKGZAmwyRBWeikcJa',
                    //     'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..rvTVS2mvza-6IlEVw3WAbg.H00Rk6U6bsY8z-wgX3JFZfLl_zV7PIbDc5prnkYqzVe-vuSt5u-NrpUcjk8Wg_QH6ez5oQtfEzwJz190ryVS-icNNOETAPdlAJ5-N1TAxeSSMTVmVoWG4UOA6coZKyTjIMlVpoHvJU7ZAujm7jkmtl3s8owmfMwN0N79vKdo3X7jGsjVifLMjsq6T_JP5bo-RlQ8msYcMHEFmcbgJ_pqnURgGD2cNQi27ZLngSl1XrP82b9ZCNn28k0poAyhKTFmF1fdb9n9rA6ViSq5kxrSihztfiFfRdp4WDhS2KsNdS2W4oTrD-Rek-E-lduAkHIyrNbAKN94UPLN15JjLQTKp3P6Y4ZkYACff2UuWTlYafRQDnTrfY8GJQXwWn271FLE7Hfqf3qTQqevhkhY_w2QV8UHgqwDu05dizkUQDIPPfqmYcjSyt5D2Hws7VbJzpMjJ6slloWK3qk2G0_z8d35wgLdQZYfBeOlSH2BYFPNXC7t6gFhedysuHKTpEaTT-lIaH70jYzznlj6LoBIavLwIUwJ0MZ8wVwvuoxWgxWbLDLw6OhEJTee2KrPoURkyq_Z-sgl7BWZVTz9fTSQEL9Q8xZ3cStd7s_C8ObRvmHQm1GSapB4y99Dt2Umaak2qbwpf90ITcosla-_VuUWEQGDQX_9M7Z5JwJJ77BZB8sdc57mZZ6XejxYgUyGdRGdREiT.VX03QFEfI32lfCg4rhnFlA',
                    //     'refresh_token' => 'Q011567509815qwSpzLxLkqLAwL0rrvhnCa9BrLMFl3ytOywUc'
                    // )
                    array(
                        'id' => '4',
                        'client' => 'ABGv9isoKMOyT3Ahwl93NTluLGEVGjuXqi73HRJDkEcdbQajc1',
                        'secret' => 'CA5kU0oFeRcoFsHmxFsJFLyc74NeZhS8e2d7iKF4',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..S1vsxHVL2Pcm5gHK4PT4Tw.uGVv5Opa2SAH4olq2dhuXCXz7KHEMGNC-ifHbOhSZUqjITMH4_VtjQVDo35ukWjRvT1EpCaJRqflKCmoJWxWHIMwYYlqhHNNdfMVHyTQytkuiJ9lb48LN-A2fqvxdSfFEpzCDM0RSoH6ahm3SenKNrW-lEXL8W7OEMZ2daWRMNPzazCREowuWNVbKk6lyue3ZSGdVJgdpzYIEj7CR6w7GGKsEiTcwnGMjeGnVQNtHojHjH19pU0QAEw5zq36QQxornRjf4UorbU3Qg_aNfRErAdahC392qZAz2zkV_xLjciSXd2N2ZV9HxeWMG4j0J6MC6nK_6xxdXZ9XQOlaDzAHpglb9vPNGa-uMaMkewGH_J4D92QbKuvib5g7ckAZ_lQPLdJO0Ekcyo8U07q8DoI97ugmgSPHvUXIdkEOR1T01hg_bWlmBgGn3cityRD0OpVD7nBf_jL6Kw4zo7U6z5IDdGdVsZ8O9Umd-goWe_UUnzzxsICXu11g1c3q0Y-37eGD1y-ujMvCwCMe1srdnPtoPwKFmbDHBL6HxlHiADK2ZWF0P9xK11MomIr2KOQHzfMDFSM3vRUyHflGMT9oFzsNgxbeOOumAEthgL1XtkndG0ZYLCf4MOrLlXRUyTpCQipomEOUSsFWsJuW04X4oOEmx0vt0eeknKJZX2Q5cgV1HWnULvHgfXMpBBJfcRzim6IP6WHhMz_kSg1t4qo5txrnofzJAqBPjD38NyQFHxV0F8.bc_88LVTj1IIWbgRRfyBbA',
                        'refresh_token' => 'AB11581840080NGQh6HsRzOWjCj62HXWHpLLliKaRYHanZThhv'
                    ),
                    array(
                        'id' => '3',
                        'client' => 'ABz4k79Je1MCQgp918cklpZ7EKvwbvEoLZocnJ4m7Nwc7zLZK6',
                        'secret' => 'N0AznENoyT3BueirTK69WpH6Hf8oPblYflQE5hTZ',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..vJyzJi1qChvIMh03AxQIQQ.lLfjEWSVzECMTZKLWF6TIs2A-FqLCaLgpZChMKH-9ZZYv46g5J6ZS0gDHP3P1aE0QTbYVWTBhKIAgZZEUC16jmlDZ0EJ2Od_lszCooUTr7Wu7-g8-JP3s-x4Cu6YR0KKdyKX9_SNlVDEnNz6-e5H8HFEXa8Q4vtbzIHRNmU-fuK6Nn4gVWthsR1xf3K58MteoGiXuL45CBpghap7cevUXiftwxPxk_9n2ox5Gy3zM4qBIde1v4xz1KnML2PX5mRuOdT7WTyIk57Et9ABnnXcV-5J43yOpampslDeOAaJEa3gdqHrw9isDSpcgzp1x9Alsw-CHoXiXfVuEob1Vfu4cftTG4WPfZN5sH2yU5t4PR07xEMI-GbBsA6_oSTWxUrykp4MLwBNhWXBwM48_k-vouq3au_wygjzjz7YEk74huzRKMYPLWmha-GcHAP9yFzj0HEQdta-VUYAyJxLGjzLpoa0Si-7AwyNz9n_yr9gmI-CeQVf-_ScJaf7chPvUYO3zZ2UMcYaIGVUs2LlflUTxrg-KqIBuYdhvovPm4_MQSfELzzaxBAq-6qrXpgWP6RiYB-Fz2tqqFpvPFvuR0fk4EGqf-lP7YjtYxXUdabjc3NgOAM0ByFugyy0wsjnyaq_yqIz6py1COMQuJ7zsZk1jOVbZQ7mrH072Aa8wlg5rR0K47Nu-kcR9h3mK1890bTELoHvF8Q7DYKTfGrv1gMloVcmShuKL7VA7bdgjXbvL30.0mGwz9xTr2v7WljG6liRPQ',
                        'refresh_token' => 'AB11581848776sIFHjk3mz631DuYX7NQPvMLlJfnZZCiWglKP1'
                    )
        ));
    }

}
