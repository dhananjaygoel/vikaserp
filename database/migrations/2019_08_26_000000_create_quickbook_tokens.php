<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuickbookTokens extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('quickbook_token')->insert(
                array(
                     array(
                        'id' => '3',
                        'client' => 'ABpdVkDFhsmsp1KNFoDuYhgAATppzXoDlw9FFa7nE2PG9hmQZv',
                        'secret' => '3lnaubZB1MIo69RmH6geLezsPJM9aD99I8HsahXK',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..PFPZG5288E6gLQOU-2-hfg.ecBlS-uXoGqTstIJFGtFw39JdgLwRgS39tOhB8hARKzUe_MMI8eYrybKnptTexL0i5LuVKBDXioq12xADc9cpSnvgweDiSfTGDTDyl2rz-irM6bAK9rZUjnQQz2XDA4hDcq1xwSWKfpfP4IYcVzIbvc01jajgLLgCfWOjFqNTAUujfECx2PT_K4iwmTx-pte5nIG_Ob7klNpU7t-t4TECpgjLvRAlGm0r2PsbSXg3y1_5ipZrFzY6bKwGKgWmJSUAhVGwBXgaw4haVNdPvITZUs9YvLP8BmciY7BVBUMN1dyqwzVdY6PvUfPD1avxWh0C59XUfmLCCOMP0bfRinBG9vmyw2-K6XPU4fFPEjB2HoO8mBHG6fnhWAl44dEQubAwsvykZ1XHiKlNhzp4HVsZPaQu1WTF6jRXtMf9IF2ZREoFkqzp2Crwj-0fa2jp7yW9luP4_yQTfBlt19E3b2GBen0RlnaiHfYBj5ZEL575tMxTnKOzQCmT2hJpvZ-dzFGbz9wEmHtU_9YzqcpbwwnHkYFvz3U9_8Gfozh72L3vx6CQi3QJV-psgatOG2-TTVczHUviecoWIECqZNENsk0g7eJMlqB-VKi0tuutNbq8TZ8AWnebZgErDrAq5NPvF6DPyDH0yGVb9lIKpQLV_RwBhzPSMY8cstHie3Z6McD8lpSKqiP3inTrsyHmQWyZLgPJ34kmzU81p7-zFgFzKVkC9MFI7jHPvKFPlWFxQtWjac.SuQ8YdwMHhehO4oKbGZmnA',
                        'refresh_token' => 'AB11575528999ayVzpPviJKyHIyf5fnnB4bt701Hw94tgqNepN'
                    ),
                     array(
                        'id' => '4',
                        'client' => 'ABB6G2Da7EJLem4XfeXEn6vgdisRSVijSzdWNRKExRHVwssNVH',
                        'secret' => 'dUOLrdPi7J0hNzndtc7uUEconleZa9BeuoknEFay',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..DE6ZVj_sAXfVKuyC5XOOCA.WYFqZ4i2PA2Qvcf98SdkLRySACnIzEPTq4tAn9yF2Ny_1pqeWpomSLSKk4Kf4AVdbU0PLhZ2O62cN-KRwk6THbhB2N-H5H2GC9GOE2X0_oaNElfkSvPBD2J_w76QYoNPKoNjTKcNSXbP1072Rsp3pYY3aU8zUwquTGB7G9reMaVDGMgp9Fhpf72SacRBU3yW0Umj1rQqoOrqW9se9KVaKMYLrC4xbbTIX1Qw3KTbXJPz7kkuVsn070vXkTjO1Xj91kWq0oZ9XAApsM_OuXMGQkzytQ30CkPdxCAlgVnUwey1Rq40BNMd0e6ujFKTD-5NaHBZm0DBgAUIRBht6N4lm9e_s9dMP8Bru-4oBo_IHHBzzA4_c-Eh4z5e-DCaZozf18jt44Ngd7dXmOZ8yLYjS4CtddLbpVdCfvt5hgAY_PhD0IUgd799nGwPy4fyONppaTuR1gXFvou3qbGn4Wq8J_HvEL8Guml34e7jeb4UA_7CF5gScUEI-0uchp2Yu8eOus-M1c8y_MYkm_mkZNofGUm0hxeHqb9SPmmcxiVrWN3sdVIasAZL0Z815AUgc-SE1DY6Dvwy4FC05N55Uzx0aGYfe3k7MhYGayOwnobfaP4qRMHtgo-ZmQCCh1vgfMDwDfL5gqZAhX3SptthKXzTZGb6SS2hSaWGqzMU7as0XjUnchnszwGgxcfZw9Z1qEcfP9eFuG-lV0A88Xhl5-j4DcdIFdh7Xf9Y_onGPymVyRY.qVt3rpUsnGZGisYfcAwC8A',
                        'refresh_token' => 'AB11575541327lfjlufw0LiOQpzGkea4FK5NZidl8gwDFC335s'
                    )
                    
        ));
        DB::table('delivery_challan')
            ->update(array('doc_number' => ''));
		
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
