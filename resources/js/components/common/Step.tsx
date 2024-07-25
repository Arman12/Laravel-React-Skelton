import React, { MouseEvent, ReactNode } from 'react';
interface StepProps {
  id?: string;
  className?: string;
  stepNumber: number;
  children: ReactNode;
  activeStep: number;
  totalStep: number;
  setActiveStep: (stepNumber: number, validate: boolean) => void;
  hideNav?: boolean;
  validStep?: [boolean];
}
function Step(props: StepProps) {
  const { id = 'stepno' + props.stepNumber, className = 'form-steps', stepNumber, children, activeStep, setActiveStep, totalStep, hideNav = false, validStep } = props;
  const goBack = (event: MouseEvent<HTMLButtonElement>) => {
    if (activeStep != 1) {
      setActiveStep(activeStep - 1, false);
    }
  };
  const goNext = (event: MouseEvent<HTMLButtonElement>) => {
    setActiveStep(activeStep + 1, true);
  };
  const shouldRenderChildren = activeStep === stepNumber;
  return (
    <div className={className} id={id} data-step={stepNumber} style={{ display: shouldRenderChildren ? 'block' : 'none' }}>
      {shouldRenderChildren && children} 
      {!hideNav && <button onClick={goNext} className={(validStep && validStep[activeStep])?'next-btn active':'next-btn'}>{(activeStep == totalStep) ? 'Submit' : 'Next'}</button>}
      {(activeStep != 1) && <button onClick={goBack} className='back-btn'>{'<< Back'}</button>}
    </div>
  );
};

export default Step;