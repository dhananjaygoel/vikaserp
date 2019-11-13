<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuickbookToken extends Seeder {

    public function run() {
        DB::table('quickbook_token')->truncate();
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
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..2op9yHtQQWE4dHsUSccV-A.HXTg32VjGZACXUVkgS96TWwI9mUPtsQiv1Zcx7xK8EoqCuQ5s-_JX0KXrp5GBLUx6294zwuMQRP1Avu33QpURaRexfukYJoDOBVvhTIS8nGAN3xi42_Ud3FhRLyf0MPMzecmqvj5ZTcOEqQZixZOSxeOwNssSF30PWZR2twYLzP_xWr0GPy0xgKtwCRrp2xm0_kXHp5aJjSEYG6nrnUKsyiEKKzmKFg790M07gebbDIg0H7HR0qJbeUWB3ik02uZqOW36VHI8PHfJBXqmgGSmaeKZuRk-KuMkt2g8zor0fBs0ndpjl2XB6NRWeoj0d0rRWnQCvfwgu_ObFg-ZOUOsNuTZ0Uu7JApSCu5ALW0NazM-IrRut62UPz0G9yAIwo11GyviH64l1_Fvyjx-oKDm94sshvT8s4sRJhyuHnktdKqqnSlHIqk-NJL_m6zNPe-K_uvHvfzQWZ8j_Fzts5pr5ezzt2f-kUY6P_W3pehO65dAL-8MrGbvz147Gql2BcxN_RQNu6OUPJUolqBHALmc_5ZTY_jEDSTDu6Qx8LLUir61zbrFU38NBywyauG0hyZpo8EOaVq_4q_UHmY8PWAA4c23w9fnD7aeby3r7In0oIbslc_s0iqNgEp8oFE4YUkbHuVB9BvHXXJCLmVLv1ftoeRGl0Dnm4kHkR_OjHt7gOJlwQkfLJwAIRVOuKkM2fKGhG3ZcK9cA7GDfZrsD363BgdAw0EOrz7GEagU1S7JAc.vG4-jKj8ge3Ns31Lj1oToQ',
                        'refresh_token' => 'AB11582351787UdqXRPBJtb3mF7lXbYNHEUOESpWkIeBowR8SE'
                    ),
                    array(
                        'id' => '3',
                        'client' => 'ABz4k79Je1MCQgp918cklpZ7EKvwbvEoLZocnJ4m7Nwc7zLZK6',
                        'secret' => 'N0AznENoyT3BueirTK69WpH6Hf8oPblYflQE5hTZ',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..s1p270xWNdyxGEuk02nw-A.GNnYd11rl_7TlMxUH0z-r9NFBsoBI0Kgos-QVD6evm0-soyT7VjCcFTOunTqeiadXkrrDgOSa9IYFSSaKJTKAhARgH4aeose73s-1ohZeHdZt8vOV58ODMRX3HQ0ITC4g9ORA7lE5mJRmxHKffDHJc1BUgQitB0yo6miGue1UUfzHS_MznINXwKBGg5kR6eqHp0nBHS1Xg3Y18Dqb_9OG0C7EWjQeaLaQI3w_1OfuNILkMEcVUYQpgNGj_tY4QO-srAqE2cxqreVoH8YVsEuVnS8BxNl8h2Uw1qfKqNIJlRUONmJvHrR64OQmIavJvuS6MUZKeAmFyNdFbRW5AWLKNWm9V6uUZIZpb1H0EmEV0XDJGdDEdW4zaoi7bi7IdXtIA_m_-YHwQ90a7NXXTqZ1ZYH81LsJHiHoZj-wNS2fY0xv_ZeovPXGqwBgLO0Vc5JbFakuC98btCDkCov1Pz-mhbHFpT-_iasgjUvHdogkOV6Ihxq-uGTIyRv1xbQmK85ICWcBX2Cp_2Y-s682Y1q0cGpI0ucJoD_1XzNZOvj4TUcZla9juNzaM0yDiGfFIJZtl10cPcZBKsclSNWkSfFWXS3h4TQRtOOopDGs73LfxbszgvT3yjwqLYB_K3Z32kdvAL05AaPbGDISildRM1iUtTSWicRI-8FDKgz1SMwiHOAOELOi_rNJFA-Vj-S3Fkxa6QftZ4zRwqtvJIhRFYO0O_fHBzID2Uf-Q6oFSzV3jc.VHKcfiz4eXAziksPFzPjLA',
                        'refresh_token' => 'AB11582350849OoNpxJdQE6byHrcTmBxPrmH4AjjJdKDQNnO5r'
                    )
        ));
    }

}
