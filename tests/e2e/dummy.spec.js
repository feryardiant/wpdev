describe('my awesome website', () => {
  it('should do some chai assertions', async () => {
    browser.maximizeWindow()
    await browser.url('/')
    const title = await browser.getTitle()

    title.should.to.be.equal('WordPress Local – Just another WordPress site')
  })
})
