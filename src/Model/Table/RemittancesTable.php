<?php
namespace App\Model\Table;
use App\Model\Entity\Remittance;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class RemittancesTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->table('remittances');
        $this->primaryKey('id');

        $this->addBehavior('Proffer.Proffer', [
            'photo' => [    // The name of your upload field
                'root' => WWW_ROOT . 'img', // Customise the root upload folder here, or omit to use the default
                'dir' => 'photo_dir',   // The name of the field to store the folder
                'thumbnailSizes' => [ // Declare your thumbnails
                    'square' => [   // Define the prefix of your thumbnail
                        'w' => 800, // Width
                        'h' => 600, // Height
                        'jpeg_quality'  => 100,
                        'crop' => true
                    ]
                ],
                'thumbnailMethod' => 'gd'   // Options are Imagick or Gd
            ],
            'ach' => [    // The name of your upload field
                'root' => WWW_ROOT . 'img', // Customise the root upload folder here, or omit to use the default
                'dir' => 'ach_dir',   // The name of the field to store the folder
                'thumbnailSizes' => [ // Declare your thumbnails
                    'square' => [   // Define the prefix of your thumbnail
                        'w' => 800, // Width
                        'h' => 600, // Height
                        'jpeg_quality'  => 100,
                        'crop' => true
                    ]
                ],
                'thumbnailMethod' => 'gd'   // Options are Imagick or Gd
            ]
        ]);
        
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->provider('proffer', 'Proffer\Model\Validation\ProfferRules')
            ->add('photo', 'proffer', [
                'rule' => ['dimensions', [
                    'min' => ['w' => 100, 'h' => 100],
                    'max' => ['w' => 3000, 'h' => 2000]
                ]],
                'message' => __('La imagen no tiene las dimensiones correctas'),
                'provider' => 'proffer'
            ])
            ->add('photo', 'extension',[
                'rule' => ['extension', [
                    'jpeg', 'jpg', 'png'
                ]],
                'message' => __('La imagen no tiene el formato de archivo correcto'),
            ])
            ->add('ach', 'proffer', [
                'rule' => ['dimensions', [
                    'min' => ['w' => 100, 'h' => 100],
                    'max' => ['w' => 3000, 'h' => 2000]
                ]],
                'message' => __('La imagen no tiene las dimensiones correctas'),
                'provider' => 'proffer'
            ])
            ->add('ach', 'extension',[
                'rule' => ['extension', [
                    'jpeg', 'jpg', 'png'
                ]],
                'message' => __('La imagen no tiene el formato de archivo correcto'),
            ])
            ->allowEmpty('photo', 'update')
            ->allowEmpty('photo_dir', 'update')
            ->allowEmpty('ach', 'update')
            ->allowEmpty('ach_dir', 'update')
            ->allowEmpty('ach', 'create')
            ->allowEmpty('ach_dir', 'create');
        return $validator;
    }
}