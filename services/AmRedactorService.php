<?php
namespace Craft;

class AmRedactorService extends BaseApplicationComponent
{
	public function getDimensionsFromStyle($styleContents)
	{
		$dimensions = array();

		foreach (array('height', 'width') as $dimension)
		{
			preg_match('/'. $dimension .':[ ]*([0-9]+)px;/', $styleContents, $match);
			$dimensions[$dimension] = isset($match[1]) ? $match[1] : 'auto';
		}

		return $dimensions;
	}

	public function getNearestTransform($dimensions)
	{
		$where = '';
		$order = '';

		foreach ($dimensions as $dimension => $value)
		{
			if ($value != 'auto')
			{
				if ($where != '')
				{
					$where .= ' AND ';
				}
				if ($order != '')
				{
					$order .= ', ';
				}

				$where .= $dimension . ' > ' . $value;
				$order .= $dimension;
			}
		}

		if ($where == '')
		{
			$where = '1 = 1';
		}

		if ($order == '')
		{
			$order = 'width DESC';
		}
		else
		{
			$order .= ' ASC';
		}

		$row = craft()->db->createCommand()
			->select('handle')
			->from('assettransforms')
			->where($where)
			->order($order)
			->limit(1)->queryRow();
		return $row['handle'];
	}
}