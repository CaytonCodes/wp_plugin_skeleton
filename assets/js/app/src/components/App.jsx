import React, { useState, useEffect } from 'react';
import styled from 'styled-components';

const AppDiv = styled.div`
  background: black;
  color: white;
`;

function App() {
  const [count, setCount] = useState(3);

  useEffect(() => {

  }, []);

  return (
    <AppDiv>
      <h3>React is running!</h3>
      <p>{count}</p>
    </AppDiv>
  );
}

export default App;