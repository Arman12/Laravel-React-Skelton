import React from 'react';
import ProgressBar from "@ramonak/react-progress-bar";
interface BarProps {
  completed: number;
  maxCompleted: number;
  bgColor?: string;
  baseBgColor?: string;
  isLabelVisible?: boolean;
}
function ProgressB(props: BarProps) {
  const { completed, maxCompleted = 100, bgColor = '#0EC779', baseBgColor = '#e0e0de', isLabelVisible = false } = props;

  return (
    <ProgressBar completed={completed} maxCompleted={maxCompleted} bgColor={bgColor} baseBgColor={baseBgColor} animateOnRender={true} isLabelVisible={isLabelVisible} />
  );
};

export default ProgressB;