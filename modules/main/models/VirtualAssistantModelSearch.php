<?php

namespace app\modules\main\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\main\models\VirtualAssistantModel;

/**
 * VirtualAssistantModelSearch represents the model behind the search form of `app\modules\main\models\VirtualAssistantModel`.
 */
class VirtualAssistantModelSearch extends VirtualAssistantModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'dialogue_model', 'target_model', 'type', 'virtual_assistant_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($id, $params)
    {
        $query = VirtualAssistantModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'dialogue_model' => $this->dialogue_model,
            'target_model' => $this->target_model,
            'type' => $this->type,
            'virtual_assistant_id' => $id,
        ]);

        return $dataProvider;
    }
}
